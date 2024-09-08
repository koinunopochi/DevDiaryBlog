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
}

const Sidebar: React.FC<SidebarProps> = ({
  items,
  className = '',
  activeItemClassName = '',
  collapsedClassName = '',
  activeItemName = null,
}) => {
  const [isOpen, setIsOpen] = useState(true);
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
        'flex flex-col text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-night-sky transition-all duration-300 ease-in-out',
        isOpen ? 'w-64' : 'w-20',
        className,
        !isOpen && collapsedClassName
      )}
    >
      <div className="flex justify-end p-4">
        <button
          onClick={toggleSidebar}
          className="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200 rounded-full p-2 hover:bg-gray-200 dark:hover:bg-gray-700"
        >
          {isOpen ? <ChevronLeft size={24} /> : <ChevronRight size={24} />}
        </button>
      </div>
      <nav className="flex-1 overflow-y-auto">
        <ul className="space-y-2 px-3">
          {items.map((item) => (
            <li key={item.name}>
              <button
                onClick={() => handleItemClick(item)}
                className={twMerge(
                  'flex items-center w-full px-4 py-3 rounded-lg text-sm transition-all duration-200 ease-in-out',
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
                    className={`${isOpen ? 'mr-3' : 'mr-0'} transition-all duration-200`}
                  >
                    {item.icon}
                  </span>
                )}
                {isOpen && (
                  <span className="transition-opacity duration-200">
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
