import React, { useCallback, useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import PreviewMarkdownEditor from '@components/atoms/markdown/previewMarkdownEditor/PreviewMarkdownEditor';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import SaveSettingsSidebar from '@components/blocks/saveSettingsSidebar/SaveSettingsSidebar';

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
  const [article, setArticle] = useState<ArticleData>({
    id: articleId || '',
    title: '',
    content: '',
    authorId: 'user0000-7595-4ed8-a3fe-1e6af26495cc',
    categoryId: '',
    tags: [],
    status: 'Draft',
  });
  const [isLoading, setIsLoading] = useState(true);
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [categories, setCategories] = useState([]);
  const [availableTags, setAvailableTags] = useState([]);

  useEffect(() => {
    const fetchArticle = async () => {
      if (articleId) {
        setIsLoading(true);
        try {
          const response = await apiClient.get(`/api/articles/${articleId}`);
          setArticle(response.article);
        } catch (error) {
          console.error('Error fetching article:', error);
        } finally {
          setIsLoading(false);
        }
      }
    };

    const fetchCategories = async () => {
      try {
        const response = await apiClient.get('/api/articles/categories');
        setCategories(response.categories);
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    };

    const fetchTags = async () => {
      try {
        const response = await apiClient.get('/api/tags/autocompletes');
        setAvailableTags(response.tag_names);
      } catch (error) {
        console.error('Error fetching tags:', error);
      }
    };

    fetchArticle();
    fetchCategories();
    fetchTags();
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

  const handleSave = async (selectedCategory: any, selectedTags: string[]) => {
    try {
      const updatedArticle = {
        ...article,
        categoryId: selectedCategory?.id,
        tags: selectedTags,
      };
      const response = await apiClient.post(
        '/api/articles/save',
        updatedArticle
      );
      console.log('Article saved:', response);
      setIsSidebarOpen(false);
    } catch (error) {
      console.error('Error saving article:', error);
    }
  };

  const handleCategoryClick = (category:any) => {
    setArticle((prevArticle) => ({
      ...prevArticle,
      categoryId: category.id,
    }));
    console.log('Category updated:', category);
  };

  const handleTagClick = (
    tagName: string,
    categoryId: string,
    tagId?: string
  ) => {
    console.log('Tag clicked:', tagName, categoryId, tagId);
  };

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-gray-900"></div>
      </div>
    );
  }

  return (
    <div className="container mx-auto p-4">
      <div className="flex items-center mb-4 space-x-4">
        <input
          type="text"
          value={article.title}
          onChange={handleTitleChange}
          placeholder="記事タイトル"
          className="flex-grow p-2 border rounded bg-white dark:bg-night-sky text-gray-900 dark:text-white"
        />
        <button
          onClick={() => setIsSidebarOpen(true)}
          className="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200"
        >
          保存
        </button>
      </div>
      <PreviewMarkdownEditor
        onImageUpload={handleImageUpload}
        getLinkCardInfo={handleLinkCardInfo}
        initialValue={article.content}
        onChange={handleContentChange}
        onUnusedImagesDetected={handleUnusedImagesDetected}
      />
      {isSidebarOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-end overflow-auto">
          <SaveSettingsSidebar
            categories={categories}
            availableTags={availableTags}
            onSave={handleSave}
            onCategoryClick={handleCategoryClick}
            onTagClick={handleTagClick}
            selectedCategoryId={article.categoryId}
          />
          <button
            onClick={() => setIsSidebarOpen(false)}
            className="absolute top-4 right-4 text-white"
          >
            閉じる
          </button>
        </div>
      )}
    </div>
  );
};

export default EditorPage;
