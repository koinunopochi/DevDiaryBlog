import React, { useState } from 'react';
import { Moon, Sun, Leaf } from 'lucide-react';
import { useTheme } from '@/contexts/ThemeContext';
import { ThemeName } from '@/themes/themes';

interface ThemeToggleProps {
  className?: string;
}

const ThemeToggle: React.FC<ThemeToggleProps> = ({ className }) => {
  const { themeName, setThemeName, theme } = useTheme();
  const [isHovered, setIsHovered] = useState(false);

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

  const getButtonStyle = () => {
    const baseStyle = {
      color: theme.colors.textPrimary,
      transition: 'all 0.3s ease',
    };

    if (isHovered) {
      return {
        ...baseStyle,
        color: theme.colors.primary,
      };
    }

    return baseStyle;
  };

  return (
    <button
      onClick={handleThemeChange}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      className={`flex items-center w-full text-left px-4 py-2 text-sm rounded-md ${className}`}
      style={getButtonStyle()}
    >
      <ThemeIcon className="mr-2" />
      {themeName === 'light' && 'ダークモードに切り替え'}
      {themeName === 'dark' && '自然テーマに切り替え'}
      {themeName === 'nature' && 'ライトモードに切り替え'}
    </button>
  );
};

export default ThemeToggle;
