import React, { useCallback, useState } from 'react';
import PreviewMarkdownEditor from '@components/atoms/markdown/previewMarkdownEditor/PreviewMarkdownEditor';

const EditorPage: React.FC = () => {
  const [content, setContent] = useState<string>('');

  const handleImageUpload = useCallback(async (file: File): Promise<string> => {
    // 仮のサンプルURLを返す
    return Promise.resolve(`https://example.com/images/${file.name}`);
  }, []);

  const handleLinkCardInfo = async (url: string) => {
    console.log('Link card info requested for:', url);
    return {
      url: url,
      imageUrl: 'https://example.com/link-image.jpg',
      title: 'Example Link Title',
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
