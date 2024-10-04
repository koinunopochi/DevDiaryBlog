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

type DisplayMode = 'editor' | 'split' | 'preview';

const PreviewMarkdownEditor: React.FC<PreviewMarkdownEditorProps> = ({
  onImageUpload,
  getLinkCardInfo,
  initialValue = '',
  onChange,
  onUnusedImagesDetected,
}) => {
  const [content, setContent] = useState(initialValue);
  const [isWideScreen, setIsWideScreen] = useState(window.innerWidth >= 768);
  const [displayMode, setDisplayMode] = useState<DisplayMode>('split');

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

  const toggleDisplayMode = () => {
    setDisplayMode((prevMode) => {
      switch (prevMode) {
        case 'editor':
          return 'split';
        case 'split':
          return 'preview';
        case 'preview':
          return 'editor';
      }
    });
  };

  const DisplayModeButton = () => (
    <button
      onClick={toggleDisplayMode}
      className="absolute top-0.5 right-1 px-2 py-1.5 rounded-md bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors z-50"
      aria-label="Toggle display mode"
      title="Toggle display mode"
    >
      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z" />
        <path d="M12 7h7v2h-7zM12 11h7v2h-7zM5 7h5v2H5zM5 11h5v2H5z" />
      </svg>
    </button>
  );

  return (
    <div className="flex flex-col h-screen">
      <div className="flex-grow overflow-hidden">
        <DisplayModeButton />
        <div
          className={`flex ${
            displayMode === 'split' && isWideScreen ? 'flex-row' : 'flex-col'
          } h-full`}
        >
          {(displayMode === 'editor' || displayMode === 'split') && (
            <div
              className={`${
                displayMode === 'split' && isWideScreen ? 'w-1/2' : 'w-full'
              } h-full`}
            >
              <SimpleMarkdownEditor
                onImageUpload={onImageUpload}
                value={content}
                onChange={handleChange}
                onUnusedImagesDetected={onUnusedImagesDetected}
                className="h-full"
              />
            </div>
          )}
          {(displayMode === 'preview' || displayMode === 'split') && (
            <div
              className={`${
                displayMode === 'split' && isWideScreen ? 'w-1/2' : 'w-full'
              } h-full overflow-auto`}
            >
              <MarkdownRenderer
                content={content}
                getLinkCardInfo={getLinkCardInfo}
                className="h-full"
              />
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default PreviewMarkdownEditor;
