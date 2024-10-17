import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Heart, Clock } from 'lucide-react';
import Tag from '@components/atoms/tag/Tag';
import { format } from 'date-fns';
import { ja } from 'date-fns/locale';

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
        <div className="absolute z-10 p-3 text-sm rounded shadow-lg w-auto bg-background-secondary text-accent1">
          <div>
            <span>{author.displayName}</span>
            <span className="text-text-secondary">(@{author.username})</span>
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
    <div className="rounded-lg p-5 transition-all duration-300 border hover:shadow-md bg-background-main border-border-primary hover:border-primary">
      <div
        className="cursor-pointer transform transition-transform duration-300 hover:scale-[1.02]"
        onClick={handleArticleClick}
      >
        <h2 className="text-xl font-semibold mb-2 text-text-primary hover:text-primary">
          {title}
        </h2>
        <div className="flex items-center justify-between text-sm mb-2 text-text-secondary">
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
            <Heart size={16} className="mr-1 text-error" />
            <span>{likes}</span>
          </div>
        </div>
        {(createdAt || updatedAt) && (
          <div className="text-xs mb-2 text-text-secondary">
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
