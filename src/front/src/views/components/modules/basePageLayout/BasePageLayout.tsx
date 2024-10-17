import { useTheme } from '@/contexts/ThemeContext';
import React from 'react';

interface BasePageLayoutProps {
  children: React.ReactNode;
  className?: string;
}

const BasePageLayout: React.FC<BasePageLayoutProps> = ({
  children,
  className = '',
}) => {
  const { theme } = useTheme();

  return (
    <div
      className={`min-h-screen ${className}`}
      style={{
        backgroundColor: theme.colors.backgroundMain,
        color: theme.colors.textPrimary,
      }}
    >
      {children}
    </div>
  );
};

export default BasePageLayout;
