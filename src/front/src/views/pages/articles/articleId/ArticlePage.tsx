import React, { useState, useEffect } from 'react';
import MarkdownRenderer from '@components/blocks/markdownRenderer/MarkdownRenderer';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { useParams } from 'react-router-dom';

interface ArticlePageProps {
  apiClient: EnhancedApiClient;
}

const ArticlePage: React.FC<ArticlePageProps> = ({ apiClient }) => {
  const { articleId } = useParams();
  const [content, setContent] = useState<string>('');
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    const fetchContent = async () => {
      if (articleId) {
        try {
          setIsLoading(true);
          const res = await apiClient.get(`/api/articles/${articleId}`);
          setContent(res.article.content);
        } catch (error) {
          console.error('Error fetching article content:', error);
          setContent('記事の読み込みに失敗しました。');
        } finally {
          setIsLoading(false);
        }
      }
    };

    fetchContent();
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

  if (isLoading) {
    return <div>読み込み中...</div>;
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <article className="prose lg:prose-xl">
        <MarkdownRenderer
          content={content}
          getLinkCardInfo={handleLinkCardInfo}
          className=""
        />
      </article>
    </div>
  );
};

export default ArticlePage;
