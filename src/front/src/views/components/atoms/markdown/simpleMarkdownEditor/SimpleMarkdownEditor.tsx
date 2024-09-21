import React, { useState, useCallback, useEffect } from 'react';
import MDEditor from '@uiw/react-md-editor';

interface SimpleMarkdownEditorProps {
  onImageUpload: (file: File) => Promise<string>;
  value?: string;
  onChange?: (value: string | undefined) => void;
}

const SimpleMarkdownEditor: React.FC<SimpleMarkdownEditorProps> = ({
  onImageUpload,
  value: initialValue,
  onChange,
}) => {
  const [value, setValue] = useState<string | undefined>(initialValue);

  useEffect(() => {
    setValue(initialValue);
  }, [initialValue]);

  const handleChange = useCallback(
    (val: string | undefined) => {
      setValue(val);
      if (onChange) {
        onChange(val);
      }
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

  const handlePaste = useCallback(
    async (pasteEvent: React.ClipboardEvent) => {
      const items = pasteEvent.clipboardData.items;
      for (let i = 0; i < items.length; i++) {
        if (items[i].type.indexOf('image') !== -1) {
          pasteEvent.preventDefault();
          const file = items[i].getAsFile();
          if (file) {
            try {
              const imageUrl = await onImageUpload(file);
              const imageMarkdown = `![uploaded-image](${imageUrl})`;
              handleChange(
                value ? `${value}\n${imageMarkdown}` : imageMarkdown
              );
            } catch (error) {
              console.error('画像のアップロードに失敗しました:', error);
              // ここでエラーハンドリングを行う（例：ユーザーへの通知）
            }
          }
        }
      }
    },
    [onImageUpload, value, handleChange]
  );

  return (
    <div className="markdown-editor-container w-full min-w-full">
      <MDEditor
        value={value}
        onChange={handleChange}
        onKeyDown={handleKeyDown}
        textareaProps={{
          onPaste: handlePaste,
        }}
        preview="edit"
        hideToolbar={true}
        height={400}
        style={{ width: '100%', minWidth: '100%' }}
      />
    </div>
  );
};

export default SimpleMarkdownEditor;
