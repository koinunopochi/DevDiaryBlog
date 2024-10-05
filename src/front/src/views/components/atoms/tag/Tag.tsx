import React from 'react';

interface TagProps {
  id: string;
  name: string;
}

const Tag: React.FC<TagProps> = ({ id, name }) => {
  return (
    <span
      id={id}
      className="bg-blue-100 dark:bg-cosmic-blue text-blue-800 dark:text-starlight text-xs sm:text-sm font-medium px-2 py-0.5 rounded"
    >
      {name}
    </span>
  );
};

export default Tag;
