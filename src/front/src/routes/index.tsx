/* eslint-disable react-refresh/only-export-components */
import React from 'react';
import { Route, Navigate } from 'react-router-dom';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import AuthService from '@/services/AuthService';
import { AccountService } from '@/services/AccountService';
import { ProfileService } from '@/services/ProfileService';
import { UserService } from '@/services/UserService';
import ArticleRootPage from '@/views/pages/articles/ArticleRootPage';
import RootPage from '@/views/pages/RootPage';

const LoginPage = React.lazy(() => import('@/views/pages/login/LoginPage'));
const RegisterPage = React.lazy(() => import('@/views/pages/register/Register'));
const EditorPage = React.lazy(() => import('@/views/pages/articles/edit/EditorPage'));
const ArticlePage = React.lazy(() => import('@/views/pages/articles/articleId/ArticlePage'));
const AccountPage = React.lazy(() => import('@/views/pages/settings/account/AccountPage'));
const ProfilePage = React.lazy(() => import('@/views/pages/settings/profile/ProfilePage'));
const ToolsRootPage = React.lazy(() => import('@/views/pages/tools/ToolsRootPage'));
const Base64Converter = React.lazy(() => import('@/views/pages/tools/base64/Base64Converter'));
const FormatTool = React.lazy(() => import('@/views/pages/tools/formatTool/FormatTool'));
const NewlineConverter = React.lazy(() => import('@/views/pages/tools/newlineConverter/NewlineConverter'));
const URLEncoderDecoder = React.lazy(() => import('@/views/pages/tools/URLEncoderDecoder/URLEncoderDecoder'));
const UnixTimestampConverter = React.lazy(() => import('@/views/pages/tools/unixTimestampConverter/UnixTimestampConverter'));
const ColorCodeConverter = React.lazy(() => import('@/views/pages/tools/colorCodeConverter/ColorCodeConverter'));
const RegexTester = React.lazy(() => import('@/views/pages/tools/regexTester/RegexTester'));
const UsernamePage = React.lazy(() => import('@/views/pages/username/UsernamePage'));
const AboutPage = React.lazy(() => import('@/views/pages/about/AboutPage'));

export const createRoutes = (
  apiClient: EnhancedApiClient,
  authService: AuthService,
  userService: UserService,
  accountService: AccountService,
  profileService: ProfileService
) => {
  const authRoutes = (
    <>
      <Route
        path="/login"
        element={<LoginPage authService={authService} />}
      />
      <Route
        path="/register"
        element={<RegisterPage authService={authService} />}
      />
    </>
  );

  const mainRoutes = (
    <>
      <Route path="/" element={<RootPage />} />
      <Route path="/about" element={<AboutPage />} />
    </>
  );

  const articleRoutes = (
    <>
      <Route path="/articles" element={<ArticleRootPage />} />
      <Route
        path="/articles/:articleId/edit"
        element={<EditorPage apiClient={apiClient} />}
      />
      <Route
        path="/articles/:articleId"
        element={<ArticlePage apiClient={apiClient} />}
      />
    </>
  );

  const settingsRoutes = (
    <>
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
    </>
  );

  const toolRoutes = (
    <>
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
    </>
  );

  const profileRoutes = (
    <Route
      path=":username"
      element={<UsernamePage apiClient={apiClient} />}
    />
  );

  return { authRoutes, mainRoutes, articleRoutes, settingsRoutes, toolRoutes, profileRoutes };
};
