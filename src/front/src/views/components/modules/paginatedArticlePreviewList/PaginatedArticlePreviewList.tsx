import React from 'react';
import ArticlePreviewList from '@components/blocks/articlePreviewList/ArticlePreviewList';
import Pagination from '@components/atoms/pagination/Pagination';
import { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';

interface PaginatedArticlePreviewListProps {
  articles: Omit<ArticlePreviewProps, 'onTagClick'>[];
  onTagClick?: (name: string, id?: string) => void;
  currentPage: number;
  totalItems: number;
  itemsPerPage: number;
  onPageChange: (page: number) => void;
}

const PaginatedArticlePreviewList: React.FC<
  PaginatedArticlePreviewListProps
> = ({
  articles,
  onTagClick,
  currentPage,
  totalItems,
  itemsPerPage,
  onPageChange,
}) => {
  const totalPages = Math.ceil(totalItems / itemsPerPage);

  return (
    <div className="space-y-6">
      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={onPageChange}
      />
      <ArticlePreviewList articles={articles} onTagClick={onTagClick} />
      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={onPageChange}
      />
    </div>
  );
};

export default PaginatedArticlePreviewList;
