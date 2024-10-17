import React, { createContext, useState, useContext, ReactNode } from 'react';
import { themes, ThemeName } from '@/themes/themes';

type ThemeContextType = {
  themeName: ThemeName;
  setThemeName: (name: ThemeName) => void;
  theme: (typeof themes)[ThemeName];
};

const ThemeContext = createContext<ThemeContextType | undefined>(undefined);

export const ThemeProvider: React.FC<{ children: ReactNode }> = ({
  children,
}) => {
  const [themeName, setThemeName] = useState<ThemeName>('light');

  const value = {
    themeName,
    setThemeName,
    theme: themes[themeName],
  };

  return (
    <ThemeContext.Provider value={value}>{children}</ThemeContext.Provider>
  );
};

export const useTheme = () => {
  const context = useContext(ThemeContext);
  if (context === undefined) {
    throw new Error('useTheme must be used within a ThemeProvider');
  }
  return context;
};
