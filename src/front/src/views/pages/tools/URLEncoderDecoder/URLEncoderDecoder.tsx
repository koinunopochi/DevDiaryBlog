import { useState } from 'react';

const URLEncoderDecoder = () => {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [mode, setMode] = useState('encode');

  const handleConvert = () => {
    if (mode === 'encode') {
      setOutput(encodeURIComponent(input));
    } else {
      try {
        setOutput(decodeURIComponent(input));
      } catch (error) {
        setOutput('エラー: 無効な入力です');
      }
    }
  };

  return (
    <div className="w-full max-w-2xl mx-auto p-6 bg-white dark:bg-night-sky border shadow-md rounded-lg">
      <h2 className="text-2xl font-bold mb-4">URL エンコーダー/デコーダー</h2>
      <div className="space-y-4">
        <textarea
          placeholder="変換するテキストを入力してください"
          value={input}
          onChange={(e) => setInput(e.target.value)}
          className="w-full h-40 p-2 border rounded"
        />
        <div className="flex space-x-4">
          <select
            value={mode}
            onChange={(e) => setMode(e.target.value)}
            className="p-2 border rounded"
          >
            <option value="encode">エンコード</option>
            <option value="decode">デコード</option>
          </select>
          <button
            onClick={handleConvert}
            className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
          >
            変換
          </button>
        </div>
        <textarea
          value={output}
          readOnly
          className="w-full h-40 p-2 border rounded bg-gray-100"
        />
      </div>
    </div>
  );
};

export default URLEncoderDecoder;
