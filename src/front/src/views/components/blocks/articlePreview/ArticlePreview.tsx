import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Heart, Clock } from 'lucide-react';
import Tag from '@components/atoms/tag/Tag';
import { format } from 'date-fns';
import { ja } from 'date-fns/locale';

// ユーザー名を省略する関数
const truncateUsername = (username: string, maxLength: number = 15) => {
  if (username.length <= maxLength) return username;
  return `${username.slice(0, maxLength)}...`;
};

const EnhancedTooltip: React.FC<{
  author: ArticlePreviewProps['author'];
  children: React.ReactNode;
}> = ({ author, children }) => {
  const [isVisible, setIsVisible] = useState(false);

  return (
    <div className="relative inline-block">
      <div
        onMouseEnter={() => setIsVisible(true)}
        onMouseLeave={() => setIsVisible(false)}
      >
        {children}
      </div>
      {isVisible && (
        <div className="absolute z-10 p-3 bg-gray-800 text-white text-sm rounded shadow-lg w-auto">
          <div>
            <span className="">{author.displayName}</span>
            <span className="text-gray-300">(@{author.username})</span>
          </div>
        </div>
      )}
    </div>
  );
};


export interface ArticlePreviewProps {
  id: string;
  title: string;
  author: {
    displayName: string;
    username: string;
    profileImage: string;
  };
  likes: number;
  tags: string[];
  createdAt?: string;
  updatedAt?: string;
  onTagClick?: (name: string, id?: string) => void;
}

const ArticlePreview: React.FC<ArticlePreviewProps> = ({
  id,
  title,
  author,
  likes,
  tags,
  createdAt,
  updatedAt,
  onTagClick,
}) => {
  const navigate = useNavigate();

  const handleArticleClick = () => {
    navigate(`/articles/${id}`);
  };

  const truncatedDisplayName = truncateUsername(author.displayName);

  const formatDate = (date: string) => {
    return format(new Date(date), 'yyyy年MM月dd日 HH:mm', { locale: ja });
  };

  return (
    <div className="bg-white dark:bg-night-sky rounded-lg p-5 transition-all duration-300 border hover:shadow-md hover:border-blue-300 dark:hover:border-blue-500">
      <div
        className="cursor-pointer transform transition-transform duration-300 hover:scale-[1.02]"
        onClick={handleArticleClick}
      >
        <h2 className="text-xl font-semibold text-gray-800 dark:text-starlight mb-2 hover:text-blue-600 dark:hover:text-blue-400">
          {title}
        </h2>
        <div className="flex items-center justify-between text-sm text-gray-600 dark:text-starlight mb-2">
          <div className="flex items-center">
            <EnhancedTooltip author={author}>
              <Link
                to={`/${author.username}`}
                className="hover:underline flex items-center"
                onClick={(e) => e.stopPropagation()}
              >
                <img
                  src={author.profileImage}
                  alt={`${author.displayName}'s profile`}
                  className="w-8 h-8 rounded-full mr-2"
                />
                {truncatedDisplayName}
              </Link>
            </EnhancedTooltip>
          </div>
          <div className="flex items-center">
            <Heart size={16} className="mr-1 text-red-500" />
            <span>{likes}</span>
          </div>
        </div>
        {(createdAt || updatedAt) && (
          <div className="text-xs text-gray-500 dark:text-gray-400 mb-2">
            <Clock size={12} className="inline-block mr-1" />
            {createdAt && <span>作成: {formatDate(createdAt)}</span>}
            {updatedAt && createdAt !== updatedAt && (
              <span className="ml-2">更新: {formatDate(updatedAt)}</span>
            )}
          </div>
        )}
      </div>
      <div className="flex flex-wrap gap-2 mt-2">
        {tags.map((tag) => (
          <Tag key={tag} name={tag} onClick={onTagClick} />
        ))}
      </div>
    </div>
  );
};

export default ArticlePreview;
