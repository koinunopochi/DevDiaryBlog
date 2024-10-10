import React from 'react';
import ArticlePreview, { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';

interface ArticlePreviewListProps {
  articles: Omit<ArticlePreviewProps, 'onTagClick'>[];
  onTagClick?: (name: string, id?: string) => void;
}

const ArticlePreviewList: React.FC<ArticlePreviewListProps> = ({
  articles,
  onTagClick,
}) => {
  return (
    <div className="space-y-4">
      {articles.map((article) => (
        <ArticlePreview key={article.id} {...article} onTagClick={onTagClick} />
      ))}
    </div>
  );
};

export default ArticlePreviewList;
