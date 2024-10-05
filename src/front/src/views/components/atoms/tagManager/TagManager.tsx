import React, { useState, useEffect } from 'react';
import { Combobox } from '@headlessui/react';
import Tag from '@components/atoms/tag/Tag';

interface TagManagerProps {
  availableTags: string[];
  initialSelectedTags?: string[];
}

const TagManager: React.FC<TagManagerProps> = ({
  availableTags,
  initialSelectedTags = [],
}) => {
  const [selectedTags, setSelectedTags] =
    useState<string[]>(initialSelectedTags);
  const [query, setQuery] = useState('');

  useEffect(() => {
    setSelectedTags(initialSelectedTags);
  }, [initialSelectedTags]);

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

  return (
    <div className="space-y-4">
      <Combobox value={null} onChange={handleTagSelect}>
        <div className="relative mt-1">
          <Combobox.Input
            className="w-full border border-gray-300 bg-white py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
            onChange={(event) => setQuery(event.target.value)}
            displayValue={() => query}
            placeholder="タグを選択または作成"
          />
          <Combobox.Options className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
            {filteredTags.map((tag) => (
              <Combobox.Option
                key={tag}
                value={tag}
                className={({ active }) =>
                  `relative cursor-default select-none py-2 pl-10 pr-4 ${
                    active ? 'bg-indigo-600 text-white' : 'text-gray-900'
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
                    active ? 'bg-indigo-600 text-white' : 'text-gray-900'
                  }`
                }
                onClick={() => handleCreateTag(query)}
              >
                "{query}" を新しいタグとして作成
              </Combobox.Option>
            )}
          </Combobox.Options>
        </div>
      </Combobox>
      <div className="flex flex-wrap gap-2">
        {selectedTags.map((tag) => (
          <Tag key={tag} name={tag} onClick={() => handleTagRemove(tag)} />
        ))}
      </div>
    </div>
  );
};

export default TagManager;
