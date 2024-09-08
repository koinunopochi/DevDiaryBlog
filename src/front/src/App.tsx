import { Route, Routes } from 'react-router-dom';
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
import DarkModeToggle from '@/views/components/atoms/darkModeToggle/DarkModeToggle';

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
      <div className="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
        <DarkModeToggle />
        <Routes>
          <Route path="/" element={<Page authService={authService} />} />
          <Route path="/button" element={<Button label="Button" />} />
          <Route
            path="/login"
            element={<LoginPage authService={authService} />}
          />
          <Route
            path="/register"
            element={<RegisterPage authService={authService} />}
          />

          {/* Settings */}
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
        </Routes>
      </div>
    </ThemeProvider>
  );
}

export default App;
