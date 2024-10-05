import React, { useState, useEffect } from 'react';
import CategoryList, {
  CategoryData,
} from '@components/atoms/categoryList/CategoryList';
import TagManager from '@components/atoms/tagManager/TagManager';

interface SaveSettingsModalProps {
  isOpen: boolean;
  onClose: () => void;
  categories: CategoryData[];
  availableTags: string[];
  onSave: (
    selectedCategory: CategoryData | null,
    selectedTags: string[]
  ) => void;
  onCategoryClick: (category: CategoryData) => void;
  onTagClick: (tagName: string, categoryId: string, tagId?: string) => void;
  selectedCategoryId?: string;
  getLinkCardInfo: (
    url: string
  ) => Promise<{ url: string; imageUrl: string; title: string }>;
  initSelectedTag:string[];
}

const SaveSettingsModal: React.FC<SaveSettingsModalProps> = ({
  isOpen,
  onClose,
  categories,
  availableTags,
  onSave,
  onCategoryClick,
  onTagClick,
  selectedCategoryId,
  getLinkCardInfo,
  initSelectedTag,
}) => {
  const [selectedCategory, setSelectedCategory] = useState<CategoryData | null>(
    null
  );
  const [selectedTags, setSelectedTags] = useState<string[]>(initSelectedTag);

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
    onClose();
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center overflow-auto">
      <div className="bg-white dark:bg-night-sky p-6 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 className="text-2xl font-bold mb-4">保存設定</h2>
        <div className="mb-6">
          <TagManager
            availableTags={availableTags}
            initialSelectedTags={selectedTags}
            label="タグ"
            onChange={setSelectedTags}
            required
          />
        </div>
        <div className="mb-6">
          <label
            htmlFor="category-list"
            className="block text-gray-700 dark:text-moonlight text-sm sm:text-base font-bold mb-2"
          >
            カテゴリー
          </label>
          <CategoryList
            id="category-list"
            categories={categories}
            onCategoryClick={handleCategoryClick}
            onTagClick={handleTagClick}
            selectedCategoryId={selectedCategory?.id || selectedCategoryId}
            getLinkCardInfo={getLinkCardInfo}
          />
        </div>
        <div className="flex justify-end space-x-4">
          <button
            onClick={onClose}
            className="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-black rounded focus:outline-none focus:shadow-outline"
          >
            キャンセル
          </button>
          <button
            onClick={handleSave}
            className="px-4 py-2 bg-cosmic-blue hover:bg-blue-600 text-white rounded focus:outline-none focus:shadow-outline"
          >
            保存
          </button>
        </div>
      </div>
    </div>
  );
};

export default SaveSettingsModal;
