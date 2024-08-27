import { useState } from 'react';
import reactLogo from './assets/react.svg';
import viteLogo from '/vite.svg';

function App() {
  const [count, setCount] = useState(0);

  return (
    <div className="container mx-auto p-8">
      <div className="flex justify-center items-center space-x-4 mb-8">
        <a href="https://vitejs.dev" target="_blank">
          <img src={viteLogo} className="h-12 w-auto" alt="Vite logo" />
        </a>
        <a href="https://react.dev" target="_blank">
          <img src={reactLogo} className="h-12 w-auto" alt="React logo" />
        </a>
      </div>

      <h1 className="text-3xl font-bold text-center mb-6">Vite + React</h1>

      <div className="card p-6 bg-white rounded-lg shadow-md">
        <button
          onClick={() => setCount((count) => count + 1)}
          className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          count is {count}
        </button>
        <p className="mt-4 text-gray-600">
          Edit <code>src/App.tsx</code> and save to test HMR
        </p>
      </div>

      <p className="mt-8 text-center text-gray-500">
        Click on the Vite and React logos to learn more
      </p>
    </div>
  );
}

export default App;
