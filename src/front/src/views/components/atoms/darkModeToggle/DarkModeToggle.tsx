import React from 'react';
import { Moon, Sun } from 'lucide-react';
import { useTheme } from '../../providers/ThemeProvider';

interface DarkModeToggleProps {
  className?: string;
}

const DarkModeToggle: React.FC<DarkModeToggleProps> = ({ className }) => {
  const { isDark, setIsDark } = useTheme();

  return (
    <button
      onClick={() => setIsDark(!isDark)}
      className={`flex items-center w-full text-left px-4 py-2 text-sm ${className}`}
    >
      {isDark ? <Sun className="mr-2" /> : <Moon className="mr-2" />}
      {isDark ? 'ライトモード' : 'ダークモード'}
    </button>
  );
};

export default DarkModeToggle;
