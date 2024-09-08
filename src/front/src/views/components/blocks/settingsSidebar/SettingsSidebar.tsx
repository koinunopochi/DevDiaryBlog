import React from 'react';
import Sidebar from '@components/atoms/sidebar/Sidebar';
import { UserCircle } from 'lucide-react';

interface SidebarItem {
  name: string;
  href: string;
  icon?: React.ReactNode;
}

interface SettingsSidebarProps {
  className?: string;
  activeItemClassName?: string;
  collapsedClassName?: string;
}

const SettingsSidebar: React.FC<SettingsSidebarProps> = ({
  className = '',
  activeItemClassName = '',
  collapsedClassName = '',
}) => {
  const items: SidebarItem[] = [
    {
      name: 'アカウント',
      href: '/settings/account',
      icon: <UserCircle size={18} />,
    },
  ];

  return (
    <Sidebar
      items={items}
      className={className}
      activeItemClassName={activeItemClassName}
      collapsedClassName={collapsedClassName}
    />
  );
};

export default SettingsSidebar;
