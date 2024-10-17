import { useState } from 'react';

const Base64Converter = () => {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [mode, setMode] = useState('encode');

  const handleConvert = () => {
    if (mode === 'encode') {
      setOutput(btoa(input));
    } else {
      try {
        setOutput(atob(input));
      } catch (error) {
        setOutput('無効なBase64入力です');
      }
    }
  };

  return (
    <div className="max-w-2xl mx-auto p-6 border rounded-lg">
      <h2 className="text-2xl font-bold mb-4">
        Base64 {mode === 'encode' ? 'エンコーダー' : 'デコーダー'}
      </h2>
      <div className="space-y-4">
        <textarea
          placeholder={`${mode === 'encode' ? 'エンコード' : 'デコード'}するテキストを入力`}
          value={input}
          onChange={(e) => setInput(e.target.value)}
          className="w-full h-32 p-2 border rounded bg-background-secondary"
        />
        <div className="flex justify-between gap-2">
          <button
            onClick={() => setMode(mode === 'encode' ? 'decode' : 'encode')}
            className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base"
          >
            {mode === 'encode' ? 'デコード' : 'エンコード'}に切り替え
          </button>
          <button
            onClick={handleConvert}
            className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base"
          >
            変換
          </button>
        </div>
        <textarea
          placeholder="結果"
          value={output}
          readOnly
          className="w-full h-32 p-2 border rounded  bg-background-secondary"
        />
      </div>
    </div>
  );
};

export default Base64Converter;
