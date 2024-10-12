import { Suspense } from 'react';
import { Routes, Route } from 'react-router-dom';
import { ThemeProvider } from '@/views/components/providers/ThemeProvider';
import PageLayout from '@/views/components/modules/pageLayout/PageLayout';
import NotFound from '@/views/components/atoms/notFound/NotFound';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import AuthService from '@/services/AuthService';
import { AccountService } from '@/services/AccountService';
import { ProfileService } from '@/services/ProfileService';
import { UserService } from '@/services/UserService';
import { createRoutes } from './routes';

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
  } = createRoutes(
    apiClient,
    authService,
    userService,
    accountService,
    profileService
  );

  return (
    <ThemeProvider>
      <div className="min-h-screen bg-white dark:bg-night-sky text-gray-900 dark:text-white max-w-full">
        <Suspense fallback={<LoadingSpinner />}>
          <Routes>
            {authRoutes}
            <Route element={<PageLayout authService={authService} />}>
              {mainRoutes}
              {articleRoutes}
              {settingsRoutes}
              {toolRoutes}
              {profileRoutes}
            </Route>
            <Route path="*" element={<NotFound />} />
          </Routes>
        </Suspense>
      </div>
    </ThemeProvider>
  );
}

export default App;
