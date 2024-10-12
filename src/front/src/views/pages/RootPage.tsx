import React, { useState, useEffect, useCallback } from 'react';
import { Newspaper, Flame, Tag, ArrowRight, User } from 'lucide-react';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';
import { Link, useNavigate } from 'react-router-dom';
import ArticlePreviewList from '../components/blocks/articlePreviewList/ArticlePreviewList';

interface ApiArticle extends Omit<ArticlePreviewProps, 'onTagClick'> {
  excerpt: string;
  createdAt: string;
  category: string;
  views?: number;
}

interface ApiResponse {
  articles: ApiArticle[];
  nextCursor: string;
  hasNextPage: boolean;
  totalItems: number;
}

const RootPage: React.FC<{ apiClient: EnhancedApiClient }> = ({
  apiClient,
}) => {
  const navigate = useNavigate();
  const [featuredPost, setFeaturedPost] = useState<ApiArticle | null>(null);
  const [recentPosts, setRecentPosts] = useState<ApiArticle[]>([]);
  const [popularPosts, setPopularPosts] = useState<ApiArticle[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchArticles = useCallback(async () => {
    if (!apiClient) return;

    try {
      setIsLoading(true);
      setError(null);
      const params = new URLSearchParams({
        limit: '10',
        sortBy: 'created_at',
        sortDirection: 'desc',
      });

      const response = await apiClient.get<ApiResponse>(
        `/api/articles/latest?${params.toString()}`
      );

      if (response.articles.length > 0) {
        setFeaturedPost(response.articles[0]);
        setRecentPosts(response.articles.slice(1, 5));
        setPopularPosts(response.articles.slice(5, 8));
      }
    } catch (error) {
      console.error('Failed to fetch articles:', error);
      setError('記事の取得に失敗しました。後でもう一度お試しください。');
    } finally {
      setIsLoading(false);
    }
  }, [apiClient]);

  useEffect(() => {
    fetchArticles();
  }, [fetchArticles]);

  const handleTagClick = useCallback(
    (name: string) => {
      navigate(`/articles?tag=${encodeURIComponent(name)}`);
    },
    [navigate]
  );

  if (isLoading) {
    return <div>記事を読み込み中...</div>;
  }

  if (error) {
    return <div className="text-red-500">{error}</div>;
  }

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {featuredPost && (
        <section className="mb-16">
          <h2 className="text-3xl font-bold mb-8">注目の記事</h2>
          <div className="bg-white dark:bg-night-sky border rounded-lg overflow-hidden">
            <img
              src="/api/placeholder/1200/600"
              alt={featuredPost.title}
              className="w-full h-64 object-cover"
            />
            <div className="p-6">
              <Link
                to={`/articles/${featuredPost.id}`}
                className="text-2xl font-semibold mb-2 block"
              >
                {featuredPost.title}
              </Link>
              <p className="text-gray-600 mb-4">{featuredPost.excerpt}</p>
              <div className="flex justify-between items-center text-sm text-gray-500">
                <span className="flex items-center">
                  <User className="w-4 h-4 mr-2" />@
                  {featuredPost.author.displayName} (
                  {featuredPost.author.username})
                </span>
                <span>
                  {new Date(featuredPost.createdAt).toLocaleDateString()}
                </span>
              </div>
            </div>
          </div>
        </section>
      )}

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        <section className="md:col-span-2">
          <h2 className="text-2xl font-bold mb-6 flex items-center">
            <Newspaper className="mr-2" /> 最新の記事
          </h2>
          <div className="space-y-4">
            <ArticlePreviewList
              articles={recentPosts}
              onTagClick={handleTagClick}
            />
          </div>
          <Link
            to="/articles"
            className="mt-6 text-blue-500 font-semibold flex items-center"
          >
            すべての記事を見る <ArrowRight className="ml-1" size={18} />
          </Link>
        </section>

        <section>
          <h2 className="text-2xl font-bold mb-6 flex items-center">
            <Flame className="mr-2" /> 人気の記事
          </h2>
          <div className="space-y-4">
            <ArticlePreviewList
              articles={popularPosts}
              onTagClick={handleTagClick}
            />
          </div>
        </section>
      </div>

      <section className="mt-16">
        <h2 className="text-2xl font-bold mb-6 flex items-center">
          <Tag className="mr-2" /> カテゴリー
        </h2>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          {[
            { name: 'フロントエンド', count: 45 },
            { name: 'バックエンド', count: 38 },
            { name: 'DevOps', count: 22 },
            { name: 'データベース', count: 19 },
            { name: 'セキュリティ', count: 15 },
          ].map((category) => (
            <Link
              key={category.name}
              to={`/articles?category=${encodeURIComponent(category.name)}`}
              className="bg-white dark:bg-night-sky border p-4 rounded-lg text-center"
            >
              <h3 className="font-semibold mb-1">{category.name}</h3>
              <span className="text-sm text-gray-500">
                {category.count} 記事
              </span>
            </Link>
          ))}
        </div>
      </section>
    </div>
  );
};

export default RootPage;
