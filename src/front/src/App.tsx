import { Route, Routes } from 'react-router-dom';
import { Page } from './components/Page';
import { Button } from './components/Button';

function App() {
  return (
    <Routes>
      <Route path="/" element={<Page />} />
      <Route path="/button" element={<Button label="Button" />} />
    </Routes>
  );
}

export default App;
