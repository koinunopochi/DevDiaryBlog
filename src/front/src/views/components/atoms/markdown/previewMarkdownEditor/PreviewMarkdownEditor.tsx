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
      className="absolute top-0.5 right-1 px-2 py-1.5 rounded-md bg-background-main hover:bg-background-secondary transition-colors z-50"
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
    <div className="flex flex-col min-h-screen mb-10">
      <div className="flex-grow relative">
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
                className="h-screen"
              />
            </div>
          )}
          {(displayMode === 'preview' || displayMode === 'split') && (
            <div
              className={`
                ml-1
                ${displayMode === 'split' && isWideScreen ? 'w-1/2' : 'w-full'} 
                ${displayMode === 'split' ? 'border border-gray-200' : ''}
                h-full rounded-md
              `}
            >
              <div className="h-7 flex items-center justify-between  border-b border-gray-200 bg-white rounded-sm">
                <span className="text-sm font-medium text-black pl-3">
                  Preview
                </span>
              </div>
              <div className="">
                {' '}
                {/* 28px is equivalent to h-7 */}
                <MarkdownRenderer
                  content={content}
                  getLinkCardInfo={getLinkCardInfo}
                  className={
                    displayMode === 'preview'
                      ? 'h-full overflow-hidden'
                      : 'h-[calc(100vh+2px)] px-3 overflow-auto'
                  }
                />
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default PreviewMarkdownEditor;
