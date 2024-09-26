import React, { useCallback, useState } from 'react';
import PreviewMarkdownEditor from '@components/atoms/markdown/previewMarkdownEditor/PreviewMarkdownEditor';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

interface EditorPageProps {
  apiClient: EnhancedApiClient;
}

const EditorPage: React.FC<EditorPageProps> = ({ apiClient }) => {
  const [content, setContent] = useState<string>('');

  const handleImageUpload = useCallback(async (file: File): Promise<string> => {
    // 仮のサンプルURLを返す
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
    setContent(value || '');
  };

  const handleUnusedImagesDetected = (unusedImages: string[]) => {
    console.log('Unused images detected:', unusedImages);
    // 仮の実装なので、ここでは何もしない
  };

  return (
    <div className="container mx-auto p-4">
      <PreviewMarkdownEditor
        onImageUpload={handleImageUpload}
        getLinkCardInfo={handleLinkCardInfo}
        initialValue=""
        onChange={handleContentChange}
        onUnusedImagesDetected={handleUnusedImagesDetected}
      />
    </div>
  );
};

export default EditorPage;
