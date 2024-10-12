import { Route, Routes, Navigate } from 'react-router-dom';
import { Page } from './views/pages/page/Page';
import { Button } from './views/components/atoms/button/Button';
import LoginPage from './views/pages/login/LoginPage';
import RegisterPage from './views/pages/register/Register';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import AuthService from '@/services/AuthService';
import AccountPage from '@/views/pages/settings/account/AccountPage';
import { AccountService } from '@/services/AccountService';
import ProfilePage from '@/views/pages/settings/profile/ProfilePage';
import { ProfileService } from '@/services/ProfileService';
import { UserService } from '@/services/UserService';
import { ThemeProvider } from '@/views/components/providers/ThemeProvider';
import NotFound from '@/views/components/atoms/notFound/NotFound';
import UsernamePage from '@/views/pages/username/UsernamePage';
import EditorPage from '@/views/pages/articles/edit/EditorPage';
import ArticlePage from './views/pages/articles/articleId/ArticlePage';
import PageLayout from './views/components/modules/pageLayout/PageLayout';
import ToolsRootPage from './views/pages/tools/ToolsRootPage';
import FormatTool from './views/pages/tools/formatTool/FormatTool';
import Base64Converter from './views/pages/tools/base64/Base64Converter';
import NewlineConverter from './views/pages/tools/newlineConverter/NewlineConverter';
import URLEncoderDecoder from './views/pages/tools/URLEncoderDecoder/URLEncoderDecoder';
import UnixTimestampConverter from './views/pages/tools/unixTimestampConverter/UnixTimestampConverter';
import ColorCodeConverter from './views/pages/tools/colorCodeConverter/ColorCodeConverter';
import RegexTester from './views/pages/tools/regexTester/RegexTester';

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

  return (
    <ThemeProvider>
      <div className="min-h-screen bg-white dark:bg-night-sky text-gray-900 dark:text-white max-w-full">
        <Routes>
          <Route
            path="/login"
            element={<LoginPage authService={authService} />}
          />
          <Route
            path="/register"
            element={<RegisterPage authService={authService} />}
          />

          {/* PageLayoutを適用するルート */}
          <Route element={<PageLayout authService={authService} />}>
            <Route path="/" element={<Page />} />
            <Route path="/button" element={<Button label="Button" />} />

            {/* Articles */}
            <Route
              path="/articles/:articleId/edit"
              element={<EditorPage apiClient={apiClient} />}
            />
            <Route
              path="/articles/:articleId"
              element={<ArticlePage apiClient={apiClient} />}
            />

            {/* Settings */}
            <Route
              path="/settings"
              element={<Navigate to="/settings/account" replace />}
            />
            <Route
              path="/settings/account"
              element={
                <AccountPage
                  initialEmail={authService.getUserEmail()}
                  initialName={authService.getUsername()}
                  onNameSubmit={(name) => accountService.updateName(name)}
                  onEmailSubmit={(email) => accountService.updateEmail(email)}
                  onPasswordSubmit={(password) =>
                    accountService.updatePassword(password)
                  }
                  checkNameAvailability={(name) =>
                    accountService.checkNameAvailability(name)
                  }
                />
              }
            />
            <Route
              path="/settings/profile"
              element={
                <ProfilePage
                  initialData={userService.getProfile()}
                  defaultProfileIcons={() =>
                    profileService.getDefaultProfileIcons()
                  }
                  onSubmit={(data) => profileService.saveProfile(data)}
                />
              }
            />

            {/* Tools */}
            <Route path="/tools" element={<ToolsRootPage />} />
            <Route path="/tools/base64" element={<Base64Converter />} />
            <Route path="/tools/format" element={<FormatTool />} />
            <Route
              path="/tools/newline-converter"
              element={<NewlineConverter />}
            />
            <Route
              path="/tools/url-encoder-decoder"
              element={<URLEncoderDecoder />}
            />
            <Route
              path="/tools/unix-timestamp-converter"
              element={<UnixTimestampConverter />}
            />
            <Route
              path="/tools/color-code-converter"
              element={<ColorCodeConverter />}
            />
            <Route
              path="/tools/regex-tester"
              element={<RegexTester />}
            />
            <Route path="/tools/*" element={<Navigate to="/tools" />} />

            {/* Profile */}
            <Route
              path=":username"
              element={<UsernamePage apiClient={apiClient} />}
            />
          </Route>

          {/* 404 ページ */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </div>
    </ThemeProvider>
  );
}

export default App;
