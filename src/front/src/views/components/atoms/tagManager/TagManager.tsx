import React, { useState, useEffect } from 'react';
import { Combobox } from '@headlessui/react';
import Tag from '@components/atoms/tag/Tag';

interface TagManagerProps {
  availableTags: string[];
  initialSelectedTags?: string[];
  label?: string;
  required?: boolean;
  error?: string;
  className?: string;
  onChange?: (selectedTags: string[]) => void;
}

const TagManager: React.FC<TagManagerProps> = ({
  availableTags,
  initialSelectedTags = [],
  label,
  required = false,
  error,
  className = '',
  onChange,
}) => {
  const [selectedTags, setSelectedTags] =
    useState<string[]>(initialSelectedTags);
  const [query, setQuery] = useState('');
  const [isTouched, setIsTouched] = useState(false);

  useEffect(() => {
    setSelectedTags(initialSelectedTags);
  }, [initialSelectedTags]);

  useEffect(() => {
    if (onChange) {
      onChange(selectedTags);
    }
  }, [selectedTags, onChange]);

  const filteredTags =
    query === ''
      ? availableTags
      : availableTags.filter((tag) =>
          tag.toLowerCase().includes(query.toLowerCase())
        );

  const handleTagSelect = (tag: string | null) => {
    if (tag && !selectedTags.includes(tag)) {
      setSelectedTags([...selectedTags, tag]);
    }
    setQuery('');
  };

  const handleTagRemove = (tagToRemove: string) => {
    setSelectedTags(selectedTags.filter((tag) => tag !== tagToRemove));
  };

  const handleCreateTag = (newTag: string) => {
    if (newTag && !selectedTags.includes(newTag)) {
      setSelectedTags([...selectedTags, newTag]);
      setQuery('');
    }
  };

  const handleBlur = () => {
    setIsTouched(true);
  };

  return (
    <div className="mb-4">
      {label && (
        <label className="block text-gray-700 dark:text-moonlight text-sm sm:text-base font-bold mb-1 sm:mb-2">
          {label}
          {required && <span className="text-red-500 ml-1">*</span>}
        </label>
      )}
      <div className="relative">
        <Combobox value={null} onChange={handleTagSelect}>
          <Combobox.Input
            className={`shadow appearance-none border rounded w-full 
              py-2 px-3 sm:py-2.5 sm:px-4
              text-sm sm:text-base
              bg-background-main
              leading-tight focus:outline-none 
              focus:shadow-outline
              box-border ${
                error && isTouched
                  ? 'border-red-500 dark:border-red-400'
                  : 'border-gray-300 dark:border-gray-600'
              } ${className}`}
            onChange={(event) => setQuery(event.target.value)}
            displayValue={() => query}
            onBlur={handleBlur}
            placeholder="タグを選択または作成"
          />
          <Combobox.Options
            className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-background-secondary
 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            {filteredTags.map((tag) => (
              <Combobox.Option
                key={tag}
                value={tag}
                className={({ active }) =>
                  `relative cursor-default select-none py-2 pl-10 pr-4 ${
                    active ? 'bg-accent1 text-inverted' : 'text-primary'
                  }`
                }
              >
                {tag}
              </Combobox.Option>
            ))}
            {query && !filteredTags.includes(query) && (
              <Combobox.Option
                value={query}
                className={({ active }) =>
                  `relative cursor-default select-none py-2 pl-10 pr-4 ${
                    active ? 'bg-accent1 text-inverted' : 'text-primary'
                  }`
                }
                onClick={() => handleCreateTag(query)}
              >
                "{query}" を新しいタグとして作成
              </Combobox.Option>
            )}
          </Combobox.Options>
        </Combobox>
      </div>
      <div className="flex flex-wrap gap-2 mt-2">
        {selectedTags.map((tag) => (
          <Tag key={tag} name={tag} onClick={() => handleTagRemove(tag)} />
        ))}
      </div>
      {error && isTouched && (
        <p className="text-red-500 dark:text-red-400 text-xs sm:text-sm italic mt-1">
          {error}
        </p>
      )}
    </div>
  );
};

export default TagManager;
