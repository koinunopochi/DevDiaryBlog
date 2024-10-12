import { NavLink } from 'react-router-dom';

const TabNavigation = () => {
  const tabs = [
    { name: 'Home', path: '/' },
    { name: 'Articles', path: '/articles' },
    { name: 'Tools', path: '/tools' },
    { name: 'About', path: '/about' },
  ];

  return (
    <nav className="bg-gray-50 shadow-sm">
      <div className="mx-auto px-4 sm:px-6 lg:px-8">
        <ul className="flex space-x-8">
          {tabs.map((tab) => (
            <li key={tab.name}>
              <NavLink
                to={tab.path}
                className={({ isActive }) =>
                  `inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out ${
                    isActive
                      ? 'border-indigo-400 text-gray-900 focus:outline-none focus:border-indigo-700'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300'
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
