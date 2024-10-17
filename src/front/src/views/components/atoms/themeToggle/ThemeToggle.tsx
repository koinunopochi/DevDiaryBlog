import React from 'react';
import { Moon, Sun, Leaf } from 'lucide-react';
import { useTheme } from '@/contexts/ThemeContext';
import { ThemeName } from '@/themes/themes';

interface ThemeToggleProps {
  className?: string;
}

const ThemeToggle: React.FC<ThemeToggleProps> = ({ className }) => {
  const { themeName, setThemeName } = useTheme();

  const themeIcons = {
    light: Sun,
    dark: Moon,
    nature: Leaf,
  };

  const nextTheme: Record<ThemeName, ThemeName> = {
    light: 'dark',
    dark: 'nature',
    nature: 'light',
  };

  const ThemeIcon = themeIcons[themeName];

  const handleThemeChange = () => {
    setThemeName(nextTheme[themeName]);
  };

  return (
    <button
      onClick={handleThemeChange}
      className={`flex items-center w-full text-left px-4 py-2 text-sm ${className}`}
    >
      <ThemeIcon className="mr-2" />
      {themeName === 'light' && 'ダークモードに切り替え'}
      {themeName === 'dark' && '自然テーマに切り替え'}
      {themeName === 'nature' && 'ライトモードに切り替え'}
    </button>
  );
};

export default ThemeToggle;
