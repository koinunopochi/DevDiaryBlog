import React from 'react';

interface BasePageLayoutProps {
  children: React.ReactNode;
  className?: string;
}

const BasePageLayout: React.FC<BasePageLayoutProps> = ({
  children,
  className = '',
}) => {
  return (
    <div
      className={`min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 ${className}`}
    >
      {children}
    </div>
  );
};

export default BasePageLayout;
