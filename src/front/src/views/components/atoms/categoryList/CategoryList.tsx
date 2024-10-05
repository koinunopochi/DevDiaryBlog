import React from 'react';
import Category from '@components/atoms/category/Category';

export interface CategoryData {
  id: string;
  name: string;
  description: string;
  tags: { id: string; name: string }[];
}

interface CategoryListProps {
  categories: CategoryData[];
  onCategoryClick: (category: CategoryData) => void;
  onTagClick: (tagName: string, categoryId: string, tagId?: string) => void;
  className?: string;
  selectedCategoryId?: string;
}

const CategoryList: React.FC<CategoryListProps> = ({
  categories,
  onCategoryClick,
  onTagClick,
  className,
  selectedCategoryId,
}) => {
  return (
    <div className={`space-y-4 ${className}`}>
      {categories.map((category) => (
        <div key={category.id} className="transition-transform hover:scale-105">
          <Category
            category={category}
            isSelected={selectedCategoryId === category.id}
            onCategoryClick={() => onCategoryClick(category)}
            onTagClick={(tagName, tagId) =>
              onTagClick(tagName, category.id, tagId)
            }
          />
        </div>
      ))}
    </div>
  );
};

export default CategoryList;
