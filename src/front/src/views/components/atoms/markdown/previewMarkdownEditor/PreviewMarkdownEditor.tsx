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
  const [isFullscreenPreview, setIsFullscreenPreview] = useState(false);

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

  const toggleFullscreenPreview = () => {
    setIsFullscreenPreview(!isFullscreenPreview);
  };

  const FullscreenButton = () => (
    <button
      onClick={toggleFullscreenPreview}
      className="absolute top-0 right-2 p-1 mt-1 rounded-md hover:bg-gray-300 transition-colors"
      aria-label="Toggle fullscreen preview"
      title="Toggle fullscreen preview"
    >
      <svg width="12" height="12" viewBox="0 0 520 520">
        <path
          fill="currentColor"
          d="M118 171.133334L118 342.200271C118 353.766938 126.675 365.333605 141.133333 365.333605L382.634614 365.333605C394.201281 365.333605 405.767948 356.658605 405.767948 342.200271L405.767948 171.133334C405.767948 159.566667 397.092948 148 382.634614 148L141.133333 148C126.674999 148 117.999999 156.675 118 171.133334zM465.353591 413.444444L370 413.444444 370 471.222222 474.0221 471.222222C500.027624 471.222222 520.254143 451 520.254143 425L520.254143 321 462.464089 321 462.464089 413.444444 465.353591 413.444444zM471.0221 43L367 43 367 100.777778 462.353591 100.777778 462.353591 196.111111 520.143647 196.111111 520.143647 89.2222219C517.254144 63.2222219 497.027624 43 471.0221 43zM57.7900547 100.777778L153.143646 100.777778 153.143646 43 46.2320439 43C20.2265191 43 0 63.2222219 0 89.2222219L0 193.222222 57.7900547 193.222222 57.7900547 100.777778zM57.7900547 321L0 321 0 425C0 451 20.2265191 471.222222 46.2320439 471.222223L150.254143 471.222223 150.254143 413.444445 57.7900547 413.444445 57.7900547 321z"
        />
      </svg>
    </button>
  );

  return (
    <div className="h-screen flex flex-col">
      <div
        className={`flex ${
          isFullscreenPreview
            ? 'flex-col'
            : isWideScreen
              ? 'flex-row'
              : 'flex-col'
        } space-x-4 space-y-4 flex-grow overflow-hidden min-h-screen`}
      >
        {!isFullscreenPreview && (
          <div
            className={`${
              isWideScreen ? 'w-1/2' : 'w-full'
            } h-full overflow-auto flex flex-col`}
          >
            <SimpleMarkdownEditor
              onImageUpload={onImageUpload}
              value={content}
              onChange={handleChange}
              onUnusedImagesDetected={onUnusedImagesDetected}
              className="flex-grow min-h-full"
            />
          </div>
        )}
        <div
          className={`${
            isFullscreenPreview ? 'w-full' : isWideScreen ? 'w-1/2' : 'w-full'
          } h-full overflow-auto relative !mt-0 flex flex-col`}
        >
          <MarkdownRenderer
            content={content}
            getLinkCardInfo={getLinkCardInfo}
            className="flex-grow min-h-full"
          />
          <FullscreenButton />
        </div>
      </div>
    </div>
  );
};

export default PreviewMarkdownEditor;
