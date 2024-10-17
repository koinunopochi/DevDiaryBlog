import { useState } from 'react';

// コメント付きJSONをパースする関数
function parseJSONWithComments(json) {
  const regex = /\\"|"(?:\\"|[^"])*"|(\/\/.*|\/\*[\s\S]*?\*\/)/g;
  const commentLess = json.replace(regex, (m, g) => (g ? '' : m));
  return JSON.parse(commentLess);
}

const FormatTool = () => {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [format, setFormat] = useState('json');

  const formatJSON = (json: string) => {
    try {
      const obj = parseJSONWithComments(json);
      return JSON.stringify(obj, null, 2);
    } catch (e) {
      return '無効なJSONです: ' + e.message;
    }
  };

  const formatXML = (xml: string) => {
    try {
      const parser = new DOMParser();
      const xmlDoc = parser.parseFromString(xml, 'text/xml');
      const serializer = new XMLSerializer();
      return serializer.serializeToString(xmlDoc).replace(/></g, '>\n<');
    } catch (e) {
      return '無効なXMLです: ' + e.message;
    }
  };

  const handleFormat = () => {
    if (format === 'json') {
      setOutput(formatJSON(input));
    } else {
      setOutput(formatXML(input));
    }
  };

  return (
    <div className="w-full max-w-2xl mx-auto p-6 border rounded-lg">
      <h2 className="text-2xl font-bold mb-4">JSONとXML整形ツール</h2>
      <div className="space-y-4">
        <select
          value={format}
          onChange={(e) => setFormat(e.target.value)}
          className="w-full p-2 border rounded bg-background-secondary"
        >
          <option value="json">JSON (コメント対応)</option>
          <option value="xml">XML</option>
        </select>
        <textarea
          placeholder={`${format.toUpperCase()}を入力してください（JSONはコメント可）`}
          value={input}
          onChange={(e) => setInput(e.target.value)}
          className="w-full h-40 p-2 border rounded bg-background-secondary"
        />
        <button
          onClick={handleFormat}
          className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base"
        >
          整形する
        </button>
        <textarea
          placeholder="整形結果"
          value={output}
          readOnly
          className="w-full h-40 p-2 border rounded bg-background-secondary"
        />
      </div>
    </div>
  );
};

export default FormatTool;
