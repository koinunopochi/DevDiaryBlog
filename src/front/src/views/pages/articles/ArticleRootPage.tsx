import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { useNavigate } from 'react-router-dom';
import { Search, ArrowUpDown } from 'lucide-react';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import InfiniteScrollArticleList from '@components/modules/infiniteScrollArticleList/InfiniteScrollArticleList';
import { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';

interface ArticleRootPageProps {
  apiClient: EnhancedApiClient;
}

interface Article extends Omit<ArticlePreviewProps, 'onTagClick'> {
  id: string;
  title: string;
  author: {
    username: string;
    displayName: string;
    profileImage: string;
  };
  likes: number;
  tags: string[];
  createdAt: string;
  updatedAt: string;
}

interface Response {
  articles: Article[];
  nextCursor: string;
  hasNextPage: boolean;
  totalItems: number;
}

const ArticleRootPage: React.FC<ArticleRootPageProps> = ({ apiClient }) => {
  const navigate = useNavigate();
  const [selectedTag, setSelectedTag] = useState('全て');
  const [initialArticles, setInitialArticles] = useState<Article[]>([]);
  const [initialNextCursor, setInitialNextCursor] = useState<string | null>(
    null
  );
  const [isInitialLoading, setIsInitialLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('desc');

  const handleTagClick = useMemo(
    () => (name: string) => {
      setSelectedTag(name);
      // タグが選択されたときの追加のロジックをここに実装できます
    },
    []
  );

  const toggleSortDirection = useCallback(() => {
    setSortDirection((prev) => (prev === 'asc' ? 'desc' : 'asc'));
  }, []);

  const fetchArticles = useCallback(
    async (cursor: string | null) => {
      if (!apiClient) return { articles: [], nextCursor: null };

      try {
        const params = new URLSearchParams({
          limit: '10',
          sortBy: 'created_at',
          sortDirection: sortDirection,
        });

        if (cursor) {
          params.append('cursor', cursor);
        }

        if (selectedTag !== '全て') {
          params.append('tag', selectedTag);
        }

        const response = await apiClient.get<Response>(
          `/api/articles/latest?${params.toString()}`
        );

        console.log('Fetched articles:', response.articles); // デバッグログ

        return {
          articles: response.articles,
          nextCursor: response.hasNextPage ? response.nextCursor : null,
        };
      } catch (error) {
        console.error('Failed to fetch articles:', error);
        setError('記事の取得に失敗しました。後でもう一度お試しください。');
        return { articles: [], nextCursor: null };
      }
    },
    [apiClient, selectedTag, sortDirection]
  );

  useEffect(() => {
    const initializeArticles = async () => {
      setIsInitialLoading(true);
      const { articles, nextCursor } = await fetchArticles(null);
      setInitialArticles(articles);
      setInitialNextCursor(nextCursor);
      setIsInitialLoading(false);
    };

    initializeArticles();
  }, [fetchArticles]);

  if (!apiClient) return null;

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="mb-8">
        <div className="relative max-w-xl mx-auto">
          <input
            type="text"
            placeholder="記事を検索..."
            className="w-full p-3 pl-12 border rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
          />
          <Search className="absolute left-4 top-3.5 text-gray-400" size={20} />
        </div>
      </div>

      <div className="flex flex-wrap justify-center items-center gap-2 mb-8">
        {[
          '全て',
          ...new Set(initialArticles.flatMap((article) => article.tags)),
        ].map((tag) => (
          <button
            key={tag}
            onClick={() => handleTagClick(tag)}
            className={`px-4 py-2 rounded-full ${
              selectedTag === tag
                ? 'bg-blue-500 text-white border'
                : 'bg-white dark:bg-night-sky text-gray-700 hover:bg-gray-100 border'
            } transition-colors duration-200`}
          >
            {tag}
          </button>
        ))}
        <button
          onClick={toggleSortDirection}
          className="flex items-center px-4 py-2 rounded-full bg-gray-200 hover:bg-gray-300 transition-colors duration-200"
        >
          <ArrowUpDown className="mr-2" size={16} />
          {sortDirection === 'asc' ? '古い順' : '新しい順'}
        </button>
      </div>

      {isInitialLoading ? (
        <p>記事を読み込み中...</p>
      ) : error ? (
        <p className="text-red-500">{error}</p>
      ) : initialArticles.length > 0 ? (
        <div className="p-2">
          <InfiniteScrollArticleList
            initialArticles={initialArticles}
            onTagClick={handleTagClick}
            fetchMoreArticles={fetchArticles}
            initialNextCursor={initialNextCursor}
          />
        </div>
      ) : (
        <p>記事がありません。</p>
      )}
    </div>
  );
};

export default ArticleRootPage;
