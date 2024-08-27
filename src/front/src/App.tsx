import { Route, Routes } from 'react-router-dom';
import { Page } from './views/pages/page/Page';
import { Button } from './views/components/atoms/button/Button';
import LoginPage from './views/pages/login/LoginPage';


function App() {
  return (
    <Routes>
      <Route path="/" element={<Page />} />
      <Route path="/button" element={<Button label="Button" />} />
      <Route path="/login" element={<LoginPage />} />
    </Routes>
  );
}

export default App;
