import { Route, Routes } from 'react-router-dom';
import { Page } from './views/pages/page/Page';
import { Button } from './views/components/atoms/button/Button';


function App() {
  return (
    <Routes>
      <Route path="/" element={<Page />} />
      <Route path="/button" element={<Button label="Button" />} />
    </Routes>
  );
}

export default App;
