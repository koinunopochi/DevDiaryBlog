import React from 'react';
import { Link } from 'react-router-dom';
import { Heart } from 'lucide-react';
import Tag from '@components/atoms/tag/Tag';

interface ArticlePreviewProps {
  id: number;
  title: string;
  author: {
    displayName: string;
    username: string;
    profileImage: string;
  };
  likes: number;
  tags: { id: string; name: string }[];
  onTagClick?: (name: string, id?: string) => void;
}

const ArticlePreview: React.FC<ArticlePreviewProps> = ({
  id,
  title,
  author,
  likes,
  tags,
  onTagClick,
}) => {
  return (
    <div className="bg-white dark:bg-cosmic-blue rounded-lg p-4 transition-shadow duration-300">
      <Link to={`/articles/${id}`} className="block mb-2">
        <h2 className="text-xl font-semibold text-gray-800 dark:text-starlight">
          {title}
        </h2>
      </Link>
      <div className="flex items-center justify-between text-sm text-gray-600 dark:text-starlight mb-2">
        <div className="flex items-center">
          <img
            src={author.profileImage}
            alt={`${author.displayName}'s profile`}
            className="w-8 h-8 rounded-full mr-2"
          />
          <Link to={`/${author.username}`} className="hover:underline">
            @{author.displayName}({author.username})
          </Link>
        </div>
        <div className="flex items-center">
          <Heart size={16} className="mr-1 text-red-500" />
          <span>{likes}</span>
        </div>
      </div>
      <div className="flex flex-wrap gap-2 mt-2">
        {tags.map((tag) => (
          <Tag key={tag.id} id={tag.id} name={tag.name} onClick={onTagClick} />
        ))}
      </div>
    </div>
  );
};

export default ArticlePreview;
