import React, { useState, useEffect } from 'react';
import MarkdownRenderer from '@components/blocks/markdownRenderer/MarkdownRenderer';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { useParams } from 'react-router-dom';

interface ArticlePageProps {
  apiClient: EnhancedApiClient;
}

interface Article {
  id: string;
  title: string;
  content: string;
  authorId: string;
  categoryId: string;
  tags: string[];
  status: string;
  createdAt: string;
  updatedAt: string;
}

interface ArticleResponse {
  message: string;
  article: Article;
}

const ArticlePage: React.FC<ArticlePageProps> = ({ apiClient }) => {
  const { articleId } = useParams();
  const [article, setArticle] = useState<Article | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchArticle = async () => {
      if (articleId) {
        try {
          setIsLoading(true);
          const res = await apiClient.get<ArticleResponse>(
            `/api/articles/${articleId}`
          );
          setArticle(res.article);
        } catch (error) {
          console.error('Error fetching article:', error);
          setError('記事の読み込みに失敗しました。');
        } finally {
          setIsLoading(false);
        }
      }
    };

    fetchArticle();
  }, [apiClient, articleId]);

  const handleLinkCardInfo = async (url: string) => {
    console.log('Link card info requested for:', url);
    const res = await apiClient.get(`/api/ogp?url=${url}`);
    return {
      url: url,
      imageUrl: res.imageUrl,
      title: res.title,
    };
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ja-JP', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="container mx-auto px-4 py-8">
        <div
          className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
          role="alert"
        >
          <strong className="font-bold">エラー：</strong>
          <span className="block sm:inline">{error}</span>
        </div>
      </div>
    );
  }

  if (!article) {
    return null;
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <article className="rounded-lg">
        <div className="p-6">
          <h1 className="text-3xl font-bold mb-4">{article.title}</h1>
          <div className="flex justify-between items-center text-sm text-gray-600 mb-6">
            <span>作成日: {formatDate(article.createdAt)}</span>
            <span>更新日: {formatDate(article.updatedAt)}</span>
          </div>
          <div className="prose lg:prose-xl max-w-none">
            <MarkdownRenderer
              content={article.content}
              getLinkCardInfo={handleLinkCardInfo}
              className=""
            />
          </div>
        </div>
        <div className="px-6 py-4">
          <div className="flex flex-wrap gap-2">
            {article.tags.map((tag, index) => (
              <span key={index} className="">
                {tag}
              </span>
            ))}
          </div>
          <div className="mt-2 text-sm">カテゴリーID: {article.categoryId}</div>
          <div className="mt-1 text-sm">ステータス: {article.status}</div>
        </div>
      </article>
    </div>
  );
};

export default ArticlePage;
