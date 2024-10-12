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
    <div className="max-w-2xl mx-auto p-6 bg-white dark:bg-night-sky border shadow-md rounded-lg">
      <h2 className="text-2xl font-bold mb-4">
        Base64 {mode === 'encode' ? 'エンコーダー' : 'デコーダー'}
      </h2>
      <div className="space-y-4">
        <textarea
          placeholder={`${mode === 'encode' ? 'エンコード' : 'デコード'}するテキストを入力`}
          value={input}
          onChange={(e) => setInput(e.target.value)}
          className="w-full h-32 p-2 border rounded"
        />
        <div className="flex justify-between">
          <button
            onClick={() => setMode(mode === 'encode' ? 'decode' : 'encode')}
            className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
          >
            {mode === 'encode' ? 'デコード' : 'エンコード'}に切り替え
          </button>
          <button
            onClick={handleConvert}
            className="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
          >
            変換
          </button>
        </div>
        <textarea
          placeholder="結果"
          value={output}
          readOnly
          className="w-full h-32 p-2 border rounded bg-gray-100"
        />
      </div>
    </div>
  );
};

export default Base64Converter;
