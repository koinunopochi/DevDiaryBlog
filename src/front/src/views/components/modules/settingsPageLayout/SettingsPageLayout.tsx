import React, { useState } from 'react';
import { twMerge } from 'tailwind-merge';
import SettingsSidebar from '@components/blocks/settingsSidebar/SettingsSidebar';
import { Menu } from 'lucide-react';

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
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);

  const toggleSidebar = () => setIsSidebarOpen(!isSidebarOpen);

  return (
    <div className={twMerge('flex min-h-screen relative', className)}>
      {/* Sidebar for larger screens */}
      <div className="hidden md:block">
        <SettingsSidebar
          activeItemName={activeItemName}
          showOpenButton={true}
        />
      </div>

      {/* Overlay sidebar for mobile */}
      {isSidebarOpen && (
        <div
          className="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
          onClick={toggleSidebar}
        >
          <div
            className="absolute left-0 top-0 h-full w-64 bg-white dark:bg-night-sky pt-14"
            onClick={(e) => e.stopPropagation()}
          >
            <SettingsSidebar
              activeItemName={activeItemName}
              showOpenButton={false}
            />
          </div>
        </div>
      )}

      {/* Main content */}
      <main className="flex-1 p-0 overflow-y-auto px-2">
        <div className="max-w-3xl mx-auto">
          <div className="flex items-center mb-6">
            <button className="mr-4 md:hidden" onClick={toggleSidebar}>
              <Menu size={24} />
            </button>
            <h1 className="text-2xl font-bold pt-3">{title}</h1>
          </div>
          {children}
        </div>
      </main>
    </div>
  );
};

export default SettingsPageLayout;
