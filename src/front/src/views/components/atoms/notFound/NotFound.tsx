import React from 'react';
import { Link } from 'react-router-dom';
import { AlertTriangle, Home } from 'lucide-react';

const NotFound: React.FC = () => {
  return (
    <div className="flex flex-col items-center justify-center min-h-screen text-gray-900 dark:text-gray-100 px-4">
      <AlertTriangle className="w-16 h-16 md:w-24 md:h-24 text-yellow-500 mb-8" />
      <h1 className="text-4xl md:text-6xl font-bold mb-4">404</h1>
      <p className="text-xl md:text-2xl mb-8 text-center">
        申し訳ありません。お探しのページが見つかりませんでした。
      </p>
      <Link
        to="/"
        className="flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
      >
        <Home className="w-5 h-5 mr-2" />
        ホームに戻る
      </Link>
    </div>
  );
};

export default NotFound;
