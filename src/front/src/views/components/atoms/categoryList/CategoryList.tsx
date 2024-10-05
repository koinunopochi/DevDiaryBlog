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
  id?: string;
  getLinkCardInfo: (
    url: string
  ) => Promise<{ url: string; imageUrl: string; title: string }>;
}

const CategoryList: React.FC<CategoryListProps> = ({
  categories,
  onCategoryClick,
  onTagClick,
  className,
  selectedCategoryId,
  id,
  getLinkCardInfo,
}) => {
  return (
    <div
      id={id}
      className={`grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 ${className}`}
    >
      {categories.map((category) => (
        <div
          key={category.id}
          className="transition-transform hover:scale-105 flex flex-col"
        >
          <Category
            category={category}
            isSelected={selectedCategoryId === category.id}
            onCategoryClick={() => onCategoryClick(category)}
            onTagClick={(tagName, tagId) =>
              onTagClick(tagName, category.id, tagId)
            }
            getLinkCardInfo={getLinkCardInfo}
          />
        </div>
      ))}
    </div>
  );
};

export default CategoryList;
