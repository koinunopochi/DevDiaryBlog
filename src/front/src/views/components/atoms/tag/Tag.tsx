import React from 'react';
import { twMerge } from 'tailwind-merge';

interface TagProps {
  id?: string;
  name: string;
  onClick?: (name: string, id?: string) => void;
  className?: string;
}

const Tag: React.FC<TagProps> = ({ id, name, onClick, className }) => {
  const handleClick = () => {
    if (onClick) {
      onClick(name, id);
    }
  };

  return (
    <span
      id={id}
      className={twMerge(
        'bg-background-secondary text-primary',
        'text-xs sm:text-sm font-medium px-2 py-0.5 rounded',
        'transition-colors',
        onClick && 'cursor-pointer hover:bg-accent2',
        onClick && 'active:bg-accent1',
        className
      )}
      onClick={handleClick}
    >
      {name}
    </span>
  );
};

export default Tag;
