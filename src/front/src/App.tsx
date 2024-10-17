import { Suspense } from 'react';
import { Routes, Route } from 'react-router-dom';
import { ThemeProvider } from '@/contexts/ThemeContext';
import PageLayout from '@/views/components/modules/pageLayout/PageLayout';
import NotFound from '@/views/components/atoms/notFound/NotFound';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import AuthService from '@/services/AuthService';
import { AccountService } from '@/services/AccountService';
import { ProfileService } from '@/services/ProfileService';
import { UserService } from '@/services/UserService';
import { createRoutes } from './routes';
import CommonMetaTags from './views/components/atoms/meta/commonMetaTags/CommonMetaTags';

const LoadingSpinner = () => <div>Loading...</div>;

function App() {
  const apiClient = new EnhancedApiClient(
    'http://localhost:8080',
    '/sanctum/csrf-cookie'
  );
  const userService = new UserService(apiClient);
  const authService = new AuthService(apiClient, userService);
  const profileService = new ProfileService(
    apiClient,
    userService,
    authService
  );
  const accountService = new AccountService(
    apiClient,
    userService,
    authService
  );

  const {
    authRoutes,
    mainRoutes,
    articleRoutes,
    settingsRoutes,
    toolRoutes,
    profileRoutes,
    wishRoutes,
    develop,
  } = createRoutes(
    apiClient,
    authService,
    userService,
    accountService,
    profileService
  );

  return (
    <ThemeProvider>
      <CommonMetaTags/>
      <div className="min-h-screen max-w-full">
        <Suspense fallback={<LoadingSpinner />}>
          <Routes>
            {authRoutes}
            <Route element={<PageLayout authService={authService} />}>
              {mainRoutes}
              {articleRoutes}
              {settingsRoutes}
              {toolRoutes}
              {profileRoutes}
              {wishRoutes}
              {develop}
            </Route>
            <Route path="*" element={<NotFound />} />
          </Routes>
        </Suspense>
      </div>
    </ThemeProvider>
  );
}

export default App;
