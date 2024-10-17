import React from 'react';
import { NavLink } from 'react-router-dom';
import { useTheme } from '@/contexts/ThemeContext';

interface Tab {
  name: string;
  path: string;
}

const TabNavigation: React.FC = () => {
  const { theme } = useTheme();

  const tabs: Tab[] = [
    { name: 'Home', path: '/' },
    { name: 'Articles', path: '/articles' },
    { name: 'Tools', path: '/tools' },
    { name: 'About', path: '/about' },
  ];

  const getTabStyle = (isActive: boolean) => ({
    borderBottomWidth: '2px',
    borderBottomStyle: 'solid',
    borderBottomColor: isActive ? theme.colors.primary : 'transparent',
    color: isActive ? theme.colors.primary : theme.colors.textSecondary,
    ':hover': {
      color: theme.colors.textPrimary,
      borderBottomColor: isActive
        ? theme.colors.primary
        : theme.colors.borderSecondary,
    },
  });

  return (
    <nav
      style={{
        backgroundColor: theme.colors.backgroundSecondary,
        boxShadow: '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
      }}
    >
      <div className="mx-auto px-4 sm:px-6 lg:px-8">
        <ul className="flex space-x-8">
          {tabs.map((tab) => (
            <li key={tab.name}>
              <NavLink
                to={tab.path}
                className={`inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out`}
                style={({ isActive }) => getTabStyle(isActive)}
              >
                {tab.name}
              </NavLink>
            </li>
          ))}
        </ul>
      </div>
    </nav>
  );
};

export default TabNavigation;
