import React, { useState, useEffect } from 'react';
import ArticlePreviewList from '@components/blocks/articlePreviewList/ArticlePreviewList';
import Pagination from '@components/atoms/pagination/Pagination';
import { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';

interface PaginatedArticlePreviewListProps {
  articles: Omit<ArticlePreviewProps, 'onTagClick'>[];
  onTagClick?: (name: string, id?: string) => void;
  itemsPerPage: number;
}

const PaginatedArticlePreviewList: React.FC<
  PaginatedArticlePreviewListProps
> = ({ articles, onTagClick, itemsPerPage }) => {
  const [currentPage, setCurrentPage] = useState(1);
  const [paginatedArticles, setPaginatedArticles] = useState<
    Omit<ArticlePreviewProps, 'onTagClick'>[]
  >([]);

  const totalPages = Math.ceil(articles.length / itemsPerPage);

  useEffect(() => {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    setPaginatedArticles(articles.slice(startIndex, endIndex));
  }, [currentPage, articles, itemsPerPage]);

  const handlePageChange = (page: number) => {
    setCurrentPage(page);
    window.scrollTo(0, 0);
  };

  return (
    <div className="space-y-6">
      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
      />
      <ArticlePreviewList
        articles={paginatedArticles}
        onTagClick={onTagClick}
      />
      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
      />
    </div>
  );
};

export default PaginatedArticlePreviewList;
