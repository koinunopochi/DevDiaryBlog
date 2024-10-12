import React, { useState, useCallback } from 'react';
import { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';
import ArticlePreviewList from '@components/blocks/articlePreviewList/ArticlePreviewList';
import { Loader2 } from 'lucide-react';

interface InfiniteScrollArticleListProps {
  initialArticles: Omit<ArticlePreviewProps, 'onTagClick'>[];
  onTagClick?: (name: string, id?: string) => void;
  fetchMoreArticles: (cursor: string | null) => Promise<{
    articles: Omit<ArticlePreviewProps, 'onTagClick'>[];
    nextCursor: string | null;
  }>;
  initialNextCursor: string | null;
}

const InfiniteScrollArticleList: React.FC<InfiniteScrollArticleListProps> = ({
  initialArticles,
  onTagClick,
  fetchMoreArticles,
  initialNextCursor,
}) => {
  const [articles, setArticles] = useState(initialArticles);
  const [nextCursor, setNextCursor] = useState(initialNextCursor);
  const [isLoading, setIsLoading] = useState(false);

  const loadMoreArticles = useCallback(async () => {
    if (isLoading || !nextCursor) return;

    setIsLoading(true);
    try {
      const { articles: newArticles, nextCursor: newNextCursor } =
        await fetchMoreArticles(nextCursor);
      setArticles((prevArticles) => [...prevArticles, ...newArticles]);
      setNextCursor(newNextCursor);
    } catch (error) {
      console.error('記事の取得中にエラーが発生しました:', error);
    } finally {
      setIsLoading(false);
    }
  }, [fetchMoreArticles, nextCursor, isLoading]);

  return (
    <div>
      <ArticlePreviewList articles={articles} onTagClick={onTagClick} />
      {nextCursor && (
        <div className="mt-8 text-center">
          <button
            onClick={loadMoreArticles}
            disabled={isLoading}
            className={`w-full sm:w-auto flex items-center justify-center bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200 text-sm sm:text-base ${
              isLoading ? 'opacity-50 cursor-not-allowed' : ''
            }`}
          >
            {isLoading ? (
              <Loader2 className="animate-spin mr-2" size={18} />
            ) : null}
            {isLoading ? '読み込み中...' : '続きを読み込む'}
          </button>
        </div>
      )}
    </div>
  );
};

export default InfiniteScrollArticleList;
