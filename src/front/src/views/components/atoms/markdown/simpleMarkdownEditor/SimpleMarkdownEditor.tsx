import React, { useState, useCallback } from 'react';
import MDEditor from '@uiw/react-md-editor';

const SimpleMarkdownEditor: React.FC = () => {
  const [value, setValue] = useState<string | undefined>('');

  const handleChange = useCallback((val: string | undefined) => {
    setValue(val);
  }, []);

  const handleKeyDown = useCallback((e: React.KeyboardEvent) => {
    if (e.key === 'Tab') {
      e.preventDefault();
      const target = e.target as HTMLTextAreaElement;
      const start = target.selectionStart;
      const end = target.selectionEnd;
      const newValue = value?.substring(0, start) + '  ' + value?.substring(end);
      setValue(newValue);
      setTimeout(() => {
        target.selectionStart = target.selectionEnd = start + 2;
      }, 0);
    }
  }, [value]);

  const handlePaste = useCallback(async (pasteEvent: React.ClipboardEvent) => {
    const items = pasteEvent.clipboardData.items;
    for (let i = 0; i < items.length; i++) {
      if (items[i].type.indexOf('image') !== -1) {
        pasteEvent.preventDefault();
        const file = items[i].getAsFile();
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            const base64 = e.target?.result as string;
            const imageMarkdown = `![pasted-image](${base64})`;
            setValue((prev) => prev ? `${prev}\n${imageMarkdown}` : imageMarkdown);
          };
          reader.readAsDataURL(file);
        }
      }
    }
  }, []);

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
