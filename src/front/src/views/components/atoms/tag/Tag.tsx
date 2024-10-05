import React from 'react';
import { twMerge } from 'tailwind-merge';

interface TagProps {
  id: string;
  name: string;
  onClick?: (id: string, name: string) => void;
  className?:string;
}

const Tag: React.FC<TagProps> = ({ id, name, onClick,className }) => {
  const handleClick = () => {
    if (onClick) {
      onClick(id, name);
    }
  };

  return (
    <span
      id={id}
      className={twMerge(
        'bg-blue-100 dark:bg-cosmic-blue text-blue-800 dark:text-starlight',
        'text-xs sm:text-sm font-medium px-2 py-0.5 rounded',
        'transition-all duration-200 ease-in-out',
        onClick &&
          'cursor-pointer hover:bg-blue-200 dark:hover:bg-cosmic-blue-light',
        onClick && 'active:bg-blue-300 dark:active:bg-cosmic-blue-dark',
        className
      )}
      onClick={handleClick}
    >
      {name}
    </span>
  );
};

export default Tag;
