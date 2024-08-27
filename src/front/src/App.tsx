import { Route, Routes } from 'react-router-dom';
import { Page } from './views/pages/page/Page';
import { Button } from './views/components/atoms/button/Button';
import LoginPage from './views/pages/login/LoginPage';
import RegisterPage from './views/pages/register/Register';


function App() {
  return (
    <Routes>
      <Route path="/" element={<Page />} />
      <Route path="/button" element={<Button label="Button" />} />
      <Route path="/login" element={<LoginPage />} />
      <Route path="/register" element={<RegisterPage />} />
    </Routes>
  );
}

export default App;
