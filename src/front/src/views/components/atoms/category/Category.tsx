import React from 'react';
import { twMerge } from 'tailwind-merge';
import TagComponents from '@components/atoms/tag/Tag';
import { Check } from 'lucide-react';

interface Tag {
  id: string;
  name: string;
}

interface CategoryProps {
  category: {
    id: string;
    name: string;
    description: string;
    tags: Tag[];
  };
  className?: string;
  isSelected?: boolean;
  onTagClick?: (tagId: string, tagName: string) => void;
}

const Category: React.FC<CategoryProps> = ({
  category,
  className,
  isSelected = false,
  onTagClick,
}) => {
  return (
    <div
      id={category.id}
      className={twMerge(
        'rounded-lg p-4 sm:p-6 max-w-sm sm:max-w-md mx-auto border relative',
        'bg-white dark:bg-night-sky',
        'text-gray-800 dark:text-gray-200',
        'transition-all duration-300 ease-in-out',
        isSelected ? 'ring-2 ring-blue-500' : '',
        className
      )}
    >
      <h2 className="text-xl sm:text-2xl font-bold mb-2 sm:mb-4">
        {category.name}
      </h2>
      <p className="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-3 sm:mb-4">
        {category.description}
      </p>
      <div className="flex flex-wrap gap-1 sm:gap-2">
        {category.tags.map((tag) => (
          <TagComponents
            key={tag.id}
            id={tag.id}
            name={tag.name}
            onClick={onTagClick}
          />
        ))}
      </div>
      {isSelected && (
        <div className="absolute top-2 right-2 bg-blue-500 rounded-full p-1">
          <Check size={16} color="white" />
        </div>
      )}
    </div>
  );
};

export default Category;
