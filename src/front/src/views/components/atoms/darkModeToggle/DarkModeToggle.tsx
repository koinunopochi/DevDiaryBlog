import React from 'react';
import { Moon, Sun } from 'lucide-react';
import { useTheme } from '../../providers/ThemeProvider';

const DarkModeToggle: React.FC = () => {
  const { isDark, setIsDark } = useTheme();

  return (
    <button
      onClick={() => setIsDark(!isDark)}
      className="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
    >
      {isDark ? <Sun size={24} /> : <Moon size={24} />}
    </button>
  );
};

export default DarkModeToggle;
