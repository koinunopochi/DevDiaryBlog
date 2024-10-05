import React, { useState, useEffect } from 'react';
import CategoryList, {
  CategoryData,
} from '@components/atoms/categoryList/CategoryList';
import TagManager from '@components/atoms/tagManager/TagManager';

interface SaveSettingsSidebarProps {
  categories: CategoryData[];
  availableTags: string[];
  onSave: (
    selectedCategory: CategoryData | null,
    selectedTags: string[]
  ) => void;
  onCategoryClick: (category: CategoryData) => void;
  onTagClick: (tagName: string, categoryId: string, tagId?: string) => void;
  selectedCategoryId?: string;
}

const SaveSettingsSidebar: React.FC<SaveSettingsSidebarProps> = ({
  categories,
  availableTags,
  onSave,
  onCategoryClick,
  onTagClick,
  selectedCategoryId,
}) => {
  const [selectedCategory, setSelectedCategory] = useState<CategoryData | null>(
    null
  );
  const [selectedTags, setSelectedTags] = useState<string[]>([]);

  useEffect(() => {
    if (selectedCategoryId) {
      const category = categories.find((cat) => cat.id === selectedCategoryId);
      if (category) {
        setSelectedCategory(category);
      }
    }
  }, [selectedCategoryId, categories]);

  const handleCategoryClick = (category: CategoryData) => {
    setSelectedCategory(category);
    onCategoryClick(category);
  };

  const handleTagClick = (
    tagName: string,
    categoryId: string,
    tagId?: string
  ) => {
    onTagClick(tagName, categoryId, tagId);
  };

  const handleSave = () => {
    onSave(selectedCategory, selectedTags);
  };

  return (
    <div className="w-64 bg-white dark:bg-night-sky p-4 shadow-lg">
      <h2 className="text-xl font-bold mb-4">保存設定</h2>
      <div className="mt-6">
        <TagManager
          availableTags={availableTags}
          initialSelectedTags={selectedTags}
          label="タグ"
          onChange={setSelectedTags}
          required
        />
      </div>
      <label
        htmlFor="category-list"
        className="block text-gray-700 dark:text-moonlight text-sm sm:text-base font-bold mb-1 sm:mb-2"
      >
        カテゴリー
      </label>
      <CategoryList
        id="category-list"
        categories={categories}
        onCategoryClick={handleCategoryClick}
        onTagClick={handleTagClick}
        selectedCategoryId={selectedCategory?.id || selectedCategoryId}
      />

      <button
        onClick={handleSave}
        className="mt-6 w-full bg-cosmic-blue hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        保存
      </button>
    </div>
  );
};

export default SaveSettingsSidebar;
