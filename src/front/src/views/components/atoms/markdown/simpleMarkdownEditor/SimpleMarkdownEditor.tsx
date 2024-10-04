import React, { useState, useCallback, useEffect, useRef } from 'react';
import MDEditor, { commands, ICommand } from '@uiw/react-md-editor';
import { useDropzone, Accept } from 'react-dropzone';

interface SimpleMarkdownEditorProps {
  onImageUpload: (file: File) => Promise<string>;
  value?: string;
  onChange?: (value: string | undefined) => void;
  onUnusedImagesDetected?: (unusedImages: string[]) => void;
  className?: string;
}

const SimpleMarkdownEditor: React.FC<SimpleMarkdownEditorProps> = ({
  onImageUpload,
  value: initialValue,
  onChange,
  onUnusedImagesDetected,
  className = '',
}) => {
  const [value, setValue] = useState<string | undefined>(initialValue);
  const [uploadedImages, setUploadedImages] = useState<string[]>([]);
  const fileInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    setValue(initialValue);
  }, [initialValue]);

  const handleChange = useCallback(
    (val: string | undefined) => {
      setValue(val);
      if (onChange) {
        onChange(val);
      }
      detectUnusedImages(val);
    },
    [onChange]
  );

  const handleKeyDown = useCallback(
    (e: React.KeyboardEvent) => {
      if (e.key === 'Tab') {
        e.preventDefault();
        const target = e.target as HTMLTextAreaElement;
        const start = target.selectionStart;
        const end = target.selectionEnd;
        const newValue =
          value?.substring(0, start) + '  ' + value?.substring(end);
        handleChange(newValue);
        setTimeout(() => {
          target.selectionStart = target.selectionEnd = start + 2;
        }, 0);
      }
    },
    [value, handleChange]
  );

  const handleImageUpload = useCallback(
    async (file: File) => {
      try {
        const imageUrl = await onImageUpload(file);
        setUploadedImages((prev) => [...prev, imageUrl]);
        const imageMarkdown = `![uploaded-image](${imageUrl})`;
        handleChange(value ? `${value}\n${imageMarkdown}` : imageMarkdown);
      } catch (error) {
        console.error('画像のアップロードに失敗しました:', error);
      }
    },
    [onImageUpload, value, handleChange]
  );

  const onDrop = useCallback(
    (acceptedFiles: File[]) => {
      acceptedFiles.forEach((file) => {
        if (file.type.startsWith('image/')) {
          handleImageUpload(file);
        }
      });
    },
    [handleImageUpload]
  );

  const acceptedFileTypes: Accept = {
    'image/*': ['.png', '.gif', '.jpeg', '.jpg'],
  };

  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop,
    accept: acceptedFileTypes,
    noClick: true,
    noKeyboard: true,
  });

  const handlePaste = useCallback(
    (e: React.ClipboardEvent) => {
      const items = e.clipboardData.items;
      for (let i = 0; i < items.length; i++) {
        if (items[i].type.indexOf('image') !== -1) {
          e.preventDefault();
          const file = items[i].getAsFile();
          if (file) {
            handleImageUpload(file);
          }
        }
      }
    },
    [handleImageUpload]
  );

  const detectUnusedImages = useCallback(
    (currentValue: string | undefined) => {
      if (currentValue && onUnusedImagesDetected) {
        const currentImages = (currentValue.match(/!\[.*?\]\((.*?)\)/g) || [])
          .map((img) => img.match(/\((.*?)\)/)?.[1])
          .filter((url): url is string => url !== undefined);

        const unusedImages = uploadedImages.filter(
          (url) => !currentImages.includes(url)
        );

        onUnusedImagesDetected(unusedImages);
      }
    },
    [uploadedImages, onUnusedImagesDetected]
  );

  useEffect(() => {
    detectUnusedImages(value);
  }, [value, detectUnusedImages]);

  // カスタム画像アップロードコマンド
  const customImageCommand: ICommand = {
    name: 'custom-image',
    keyCommand: 'custom-image',
    buttonProps: { 'aria-label': 'Insert image' },
    icon: (
      <svg width="12" height="12" viewBox="0 0 20 20">
        <path
          fill="currentColor"
          d="M15 9c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm4-7H1c-.55 0-1 .45-1 1v14c0 .55.45 1 1 1h18c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 13l-6-5-2 2-4-5-4 8V4h16v11z"
        />
      </svg>
    ),
    execute: () => {
      fileInputRef.current?.click();
    },
  };

  // カスタムツールバーの定義
  const customToolbarCommands = [
    commands.bold,
    commands.italic,
    commands.strikethrough,
    commands.hr,
    commands.divider,
    commands.link,
    commands.quote,
    commands.code,
    commands.codeBlock,
    customImageCommand,
    commands.divider,
    commands.unorderedListCommand,
    commands.orderedListCommand,
    commands.checkedListCommand,
  ];

  const handleFileInputChange = (
    event: React.ChangeEvent<HTMLInputElement>
  ) => {
    const file = event.target.files?.[0];
    if (file) {
      handleImageUpload(file);
    }
  };
  const editorRef = useRef(null);

  return (
    <div
      {...getRootProps()}
      className="w-full h-full relative border border-gray-300 rounded-lg shadow-sm"
      ref={editorRef}
    >
      <input {...getInputProps()} className="hidden" />
      <input
        type="file"
        ref={fileInputRef}
        onChange={handleFileInputChange}
        accept="image/*"
        style={{ display: 'none' }}
      />
      <MDEditor
        value={value}
        height="100%"
        onChange={handleChange}
        onKeyDown={handleKeyDown}
        textareaProps={{
          onPaste: handlePaste,
          className: `w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-night-sky text-gray-900 dark:text-white ${className}`,
        }}
        preview="edit"
        hideToolbar={false}
        commands={customToolbarCommands}
        extraCommands={[]}
        className="w-full h-full"
      />
      {isDragActive && (
        <div className="absolute inset-0 bg-blue-100 bg-opacity-75 flex items-center justify-center text-blue-700 font-semibold">
          画像をドロップしてアップロード
        </div>
      )}
    </div>
  );
};

export default SimpleMarkdownEditor;
