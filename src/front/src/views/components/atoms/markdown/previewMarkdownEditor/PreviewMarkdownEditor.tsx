import React, { useState, useCallback, useEffect } from 'react';
import SimpleMarkdownEditor from '@components/atoms/markdown/simpleMarkdownEditor/SimpleMarkdownEditor';
import MarkdownRenderer from '@components/blocks/markdownRenderer/MarkdownRenderer';

interface PreviewMarkdownEditorProps {
  onImageUpload: (file: File) => Promise<string>;
  getLinkCardInfo: (
    url: string
  ) => Promise<{ url: string; imageUrl: string; title: string }>;
  initialValue?: string;
  onChange?: (value: string | undefined) => void;
  onUnusedImagesDetected?: (unusedImages: string[]) => void;
}

const PreviewMarkdownEditor: React.FC<PreviewMarkdownEditorProps> = ({
  onImageUpload,
  getLinkCardInfo,
  initialValue = '',
  onChange,
  onUnusedImagesDetected,
}) => {
  const [content, setContent] = useState(initialValue);
  const [isWideScreen, setIsWideScreen] = useState(window.innerWidth >= 768);

  useEffect(() => {
    const handleResize = () => {
      setIsWideScreen(window.innerWidth >= 768);
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const handleChange = useCallback(
    (value: string | undefined) => {
      setContent(value || '');
      if (onChange) {
        onChange(value);
      }
    },
    [onChange]
  );

  return (
    <div
      className={`flex ${isWideScreen ? 'flex-row' : 'flex-col'} space-x-4 space-y-4 h-full`}
    >
      <div
        className={`${isWideScreen ? 'w-1/2' : 'w-full'} h-full overflow-auto`}
      >
        <SimpleMarkdownEditor
          onImageUpload={onImageUpload}
          value={content}
          onChange={handleChange}
          onUnusedImagesDetected={onUnusedImagesDetected}
        />
      </div>
      <div
        className={`${isWideScreen ? 'w-1/2' : 'w-full'} h-full overflow-auto`}
      >
        <MarkdownRenderer
          content={content}
          getLinkCardInfo={getLinkCardInfo}
          className=""
        />
      </div>
    </div>
  );
};

export default PreviewMarkdownEditor;
