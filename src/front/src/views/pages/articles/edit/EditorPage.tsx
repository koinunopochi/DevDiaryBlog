import React, { useCallback, useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import PreviewMarkdownEditor from '@components/atoms/markdown/previewMarkdownEditor/PreviewMarkdownEditor';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

interface EditorPageProps {
  apiClient: EnhancedApiClient;
}

interface ArticleData {
  id: string;
  title: string;
  content: string;
  authorId: string;
  categoryId: string;
  tags: string[];
  status: 'Draft' | 'Published';
}

const EditorPage: React.FC<EditorPageProps> = ({ apiClient }) => {
  const { articleId } = useParams();
  const navigate = useNavigate();
  const [article, setArticle] = useState<ArticleData>({
    id: articleId || '',
    title: '',
    content: '',
    authorId: 'user0000-7595-4ed8-a3fe-1e6af26495cc', // デフォルト値を設定
    categoryId: 'ArtCATId-6735-412c-95ba-6c37f19c3680', // デフォルト値を設定
    tags: [],
    status: 'Draft',
  });

  useEffect(() => {
    const fetchArticle = async () => {
      if (articleId) {
        try {
          const response = await apiClient.get(`/api/articles/${articleId}`);
          setArticle(response.article);
        } catch (error) {
          console.error('Error fetching article:', error);
          // エラーハンドリング（例：エラーメッセージを表示）
        }
      }
    };

    fetchArticle();
  }, [apiClient, articleId]);

  const handleImageUpload = useCallback(async (file: File): Promise<string> => {
    // 実際の画像アップロード処理を実装する必要があります
    return Promise.resolve(`https://example.com/images/${file.name}`);
  }, []);

  const handleLinkCardInfo = async (url: string) => {
    console.log('Link card info requested for:', url);
    const res = await apiClient.get(`/api/ogp?url=${url}`);
    return {
      url: url,
      imageUrl: res.imageUrl,
      title: res.title,
    };
  };

  const handleContentChange = (value: string | undefined) => {
    setArticle((prev) => ({ ...prev, content: value || '' }));
  };

  const handleTitleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setArticle((prev) => ({ ...prev, title: event.target.value }));
  };

  const handleUnusedImagesDetected = (unusedImages: string[]) => {
    console.log('Unused images detected:', unusedImages);
    // 未使用画像の処理を実装する必要があります
  };

  const handleSave = async () => {
    try {
      console.log('article', article);
      const response = await apiClient.post('/api/articles/save', article);
      console.log('Article saved:', response);
      // 保存成功後の処理（例：成功メッセージを表示、記事一覧ページへリダイレクトなど）
      // navigate('/articles'); // 記事一覧ページへリダイレクト
    } catch (error) {
      console.error('Error saving article:', error);
      // エラーハンドリング（例：エラーメッセージを表示）
    }
  };

  return (
    <div className="container mx-auto p-4">
      <input
        type="text"
        value={article.title}
        onChange={handleTitleChange}
        placeholder="記事タイトル"
        className="w-full mb-4 p-2 border rounded"
      />
      <PreviewMarkdownEditor
        onImageUpload={handleImageUpload}
        getLinkCardInfo={handleLinkCardInfo}
        initialValue={article.content}
        onChange={handleContentChange}
        onUnusedImagesDetected={handleUnusedImagesDetected}
      />
      <button
        onClick={handleSave}
        className="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      >
        保存
      </button>
    </div>
  );
};

export default EditorPage;
