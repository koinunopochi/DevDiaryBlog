import React, { useState, useCallback, useEffect } from 'react';
import MDEditor, { commands } from '@uiw/react-md-editor';
import { useDropzone, Accept } from 'react-dropzone';

interface SimpleMarkdownEditorProps {
  onImageUpload: (file: File) => Promise<string>;
  value?: string;
  onChange?: (value: string | undefined) => void;
  onUnusedImagesDetected?: (unusedImages: string[]) => void;
}

const SimpleMarkdownEditor: React.FC<SimpleMarkdownEditorProps> = ({
  onImageUpload,
  value: initialValue,
  onChange,
  onUnusedImagesDetected,
}) => {
  const [value, setValue] = useState<string | undefined>(initialValue);
  const [uploadedImages, setUploadedImages] = useState<string[]>([]);

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

  // カスタムツールバーの定義
  const customToolbarCommands = [
    commands.bold,
    commands.italic,
    commands.strikethrough,
    commands.hr,
    commands.group(
      [
        commands.title2,
        commands.title3,
        commands.title4,
        commands.title5,
        commands.title6,
      ],
      {
        name: 'title',
        groupName: 'title',
        buttonProps: { 'aria-label': 'Insert title' },
      }
    ),
    commands.divider,
    commands.link,
    commands.quote,
    commands.code,
    commands.codeBlock,
    commands.image,
    commands.divider,
    commands.unorderedListCommand,
    commands.orderedListCommand,
    commands.checkedListCommand,
  ];

  return (
    <div
      {...getRootProps()}
      className="w-full min-w-full relative border border-gray-300 rounded-lg shadow-sm overflow-hidden"
    >
      <input {...getInputProps()} className="hidden" />
      <MDEditor
        value={value}
        onChange={handleChange}
        onKeyDown={handleKeyDown}
        textareaProps={{
          onPaste: handlePaste,
          className:
            'w-full min-w-full p-4 focus:outline-none focus:ring-2 focus:ring-blue-500',
        }}
        preview="edit"
        hideToolbar={false}
        commands={customToolbarCommands}
        height={400}
        className="w-full min-w-full"
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
