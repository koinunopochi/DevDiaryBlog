import { Route, Routes } from 'react-router-dom';
import { Page } from './views/pages/page/Page';
import { Button } from './views/components/atoms/button/Button';
import LoginPage from './views/pages/login/LoginPage';
import RegisterPage from './views/pages/register/Register';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import AuthService from '@/services/AuthService';

function App() {
  const apiClient = new EnhancedApiClient(
    'http://localhost:8080',
    '/sanctum/csrf-cookie'
  );
  const authService = new AuthService(apiClient);

  return (
    <Routes>
      <Route path="/" element={<Page authService={authService} />} />
      <Route path="/button" element={<Button label="Button" />} />
      <Route path="/login" element={<LoginPage authService={authService} />} />
      <Route
        path="/register"
        element={<RegisterPage authService={authService} />}
      />
    </Routes>
  );
}

export default App;
