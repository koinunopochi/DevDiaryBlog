import React, { useState } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { twMerge } from 'tailwind-merge';
import { useNavigate } from 'react-router-dom';

interface SidebarItem {
  name: string;
  href: string;
  icon?: React.ReactNode;
}

interface SidebarProps {
  items: SidebarItem[];
  className?: string;
  activeItemName?: string;
  activeItemClassName?: string;
  collapsedClassName?: string;
  showOpenButton?: boolean;
  isOpenProp?: boolean;
}

const Sidebar: React.FC<SidebarProps> = ({
  items,
  className = '',
  activeItemClassName = '',
  collapsedClassName = '',
  activeItemName = null,
  showOpenButton = true,
  isOpenProp = true,
}) => {
  const [isOpen, setIsOpen] = useState(isOpenProp);
  const [activeItem, setActiveItem] = useState<string | null>(activeItemName);
  const navigate = useNavigate();

  const toggleSidebar = () => setIsOpen(!isOpen);

  const handleItemClick = (item: SidebarItem) => {
    setActiveItem(item.name);
    navigate(item.href);
  };

  return (
    <div
      className={twMerge(
        'flex flex-col text-gray-800 dark:text-gray-200 dark:bg-night-sky transition-all duration-300 ease-in-out',
        isOpen ? 'w-64 sm:w-72 lg:w-80' : 'w-16 sm:w-20',
        className,
        !isOpen && collapsedClassName
      )}
    >
      {showOpenButton && (
        <div className="flex justify-end p-2 sm:p-3 lg:p-4">
          <button
            onClick={toggleSidebar}
            className="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200 rounded-full p-1 sm:p-2 hover:bg-gray-200 dark:hover:bg-gray-700"
          >
            {isOpen ? <ChevronLeft size={20} /> : <ChevronRight size={20} />}
          </button>
        </div>
      )}
      <nav className="flex-1 overflow-y-auto">
        <ul className="space-y-1 sm:space-y-2 px-2 sm:px-3">
          {items.map((item) => (
            <li key={item.name}>
              <button
                onClick={() => handleItemClick(item)}
                className={twMerge(
                  'flex items-center w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-sm transition-all duration-200 ease-in-out',
                  'hover:bg-white dark:hover:bg-gray-700 hover:shadow-md dark:hover:shadow-gray-800',
                  activeItem === item.name
                    ? activeItemClassName ||
                        'bg-blue-50 dark:bg-cosmic-blue text-blue-600 dark:text-starlight shadow-inner dark:shadow-gray-800'
                    : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-starlight',
                  !isOpen && 'justify-center'
                )}
              >
                {item.icon && (
                  <span
                    className={`${isOpen ? 'mr-2 sm:mr-3' : 'mr-0'} transition-all duration-200`}
                  >
                    {item.icon}
                  </span>
                )}
                {isOpen && (
                  <span className="transition-opacity duration-200 text-xs sm:text-sm">
                    {item.name}
                  </span>
                )}
              </button>
            </li>
          ))}
        </ul>
      </nav>
    </div>
  );
};

export default Sidebar;
