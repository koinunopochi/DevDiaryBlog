import React from 'react';
import { twMerge } from 'tailwind-merge';
import SettingsSidebar from '@components/blocks/settingsSidebar/SettingsSidebar';

interface SettingsPageLayoutProps {
  title: string;
  children: React.ReactNode;
  className?: string;
  activeItemName?: string;
}

const SettingsPageLayout: React.FC<SettingsPageLayoutProps> = ({
  title,
  children,
  className = '',
  activeItemName,
}) => {
  return (
    <div className={twMerge('flex min-h-screen', className)}>
      <SettingsSidebar activeItemName={activeItemName} />
      <main className="flex-1 p-6 overflow-y-auto">
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl font-bold mb-6">{title}</h1>
          {children}
        </div>
      </main>
    </div>
  );
};

export default SettingsPageLayout;
