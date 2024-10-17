import { useState } from 'react';

const NewlineConverter = () => {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [newlineChar, setNewlineChar] = useState('\\n');
  const [customChar, setCustomChar] = useState('');

  const handleConvert = () => {
    const replacementChar = newlineChar === 'custom' ? customChar : newlineChar;
    const converted = input.replace(/\r?\n/g, replacementChar);
    setOutput(converted);
  };

  return (
    <div className="w-full max-w-2xl mx-auto p-6 border rounded-lg">
      <h2 className="text-2xl font-bold mb-4">改行文字変換ツール</h2>
      <div className="space-y-4">
        <textarea
          placeholder="変換するテキストを入力してください"
          value={input}
          onChange={(e) => setInput(e.target.value)}
          className="w-full h-40 p-2 border rounded bg-background-secondary"
        />
        <div className="flex space-x-4">
          <select
            value={newlineChar}
            onChange={(e) => setNewlineChar(e.target.value)}
            className="p-2 border rounded bg-background-secondary"
          >
            <option value="\\n">\\n (LF)</option>
            <option value="\\r\\n">\\r\\n (CRLF)</option>
            <option value="\\r">\\r (CR)</option>
            <option value="custom">カスタム</option>
          </select>
          {newlineChar === 'custom' && (
            <input
              type="text"
              placeholder="カスタム改行文字"
              value={customChar}
              onChange={(e) => setCustomChar(e.target.value)}
              className="p-2 border rounded bg-background-secondary"
            />
          )}
        </div>
        <button
          onClick={handleConvert}
          className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base"
        >
          変換
        </button>
        <textarea
          value={output}
          readOnly
          className="w-full h-40 p-2 border rounded font-mono bg-background-secondary"
        />
      </div>
    </div>
  );
};

export default NewlineConverter;
