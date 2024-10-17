import React from 'react';
import { NavLink } from 'react-router-dom';

interface Tab {
  name: string;
  path: string;
}

const TabNavigation: React.FC = () => {
  const tabs: Tab[] = [
    { name: 'Home', path: '/' },
    { name: 'Articles', path: '/articles' },
    { name: 'Tools', path: '/tools' },
    { name: 'About', path: '/about' },
  ];

  return (
    <nav className="bg-background-secondary shadow-sm">
      <div className="mx-auto px-4 sm:px-6 lg:px-8">
        <ul className="flex space-x-8">
          {tabs.map((tab) => (
            <li key={tab.name}>
              <NavLink
                to={tab.path}
                className={({ isActive }) =>
                  `inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out
                  border-b-2 ${
                    isActive
                      ? 'border-primary text-primary'
                      : 'border-transparent text-text-secondary hover:text-text-primary hover:border-border-secondary'
                  }`
                }
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
