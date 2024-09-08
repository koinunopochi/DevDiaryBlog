import React from 'react';
import Sidebar from '@components/atoms/sidebar/Sidebar';
import { UserCircle, UserCog } from 'lucide-react';

interface SidebarItem {
  name: string;
  href: string;
  icon?: React.ReactNode;
}

interface SettingsSidebarProps {
  className?: string;
  activeItemName?: string;
  activeItemClassName?: string;
  collapsedClassName?: string;
}

const SettingsSidebar: React.FC<SettingsSidebarProps> = ({
  className = '',
  activeItemClassName = '',
  collapsedClassName = '',
  activeItemName,
}) => {
  const items: SidebarItem[] = [
    {
      name: 'アカウント',
      href: '/settings/account',
      icon: <UserCircle size={18} />,
    },
    {
      name: 'プロフィール',
      href: '/settings/profile',
      icon: <UserCog size={18} />,
    },
  ];

  return (
    <Sidebar
      items={items}
      activeItemName={activeItemName}
      className={className}
      activeItemClassName={activeItemClassName}
      collapsedClassName={collapsedClassName}
    />
  );
};

export default SettingsSidebar;
