import React, { useState, useEffect } from 'react';

const RegexTester: React.FC = () => {
  const [pattern, setPattern] = useState('');
  const [testString, setTestString] = useState('');
  const [flags, setFlags] = useState({
    global: true,
    ignoreCase: false,
    multiline: false,
  });
  const [matches, setMatches] = useState<string[]>([]);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    testRegex();
  }, [pattern, testString, flags]);

  const testRegex = () => {
    setError(null);
    setMatches([]);

    if (!pattern) return;

    try {
      const flagString =
        (flags.global ? 'g' : '') +
        (flags.ignoreCase ? 'i' : '') +
        (flags.multiline ? 'm' : '');
      const regex = new RegExp(pattern, flagString);
      const result = [];
      let match;
      while ((match = regex.exec(testString)) !== null) {
        result.push(match[0]);
        if (!flags.global) break; // グローバルフラグがない場合は1回だけマッチ
      }
      setMatches(result);
    } catch (err) {
      setError((err as Error).message);
    }
  };

  return (
    <div className="w-full max-w-2xl mx-auto p-6 border rounded-lg">
      <h2 className="text-2xl font-bold mb-4">正規表現テスター</h2>
      <div className="space-y-4">
        <div>
          <label htmlFor="pattern" className="block text-sm font-medium">
            正規表現パターン
          </label>
          <input
            id="pattern"
            type="text"
            value={pattern}
            onChange={(e) => setPattern(e.target.value)}
            className="mt-1 block w-full p-2 border rounded-md shadow-sm bg-background-secondary"
            placeholder="例: \b\w+\b"
          />
        </div>
        <div>
          <label htmlFor="testString" className="block text-sm font-medium">
            テスト文字列
          </label>
          <textarea
            id="testString"
            value={testString}
            onChange={(e) => setTestString(e.target.value)}
            className="mt-1 block w-full p-2 border rounded-md shadow-sm bg-background-secondary"
            rows={4}
            placeholder="テストしたい文字列を入力してください"
          />
        </div>
        <div className="flex space-x-4">
          {Object.entries(flags).map(([key, value]) => (
            <label key={key} className="inline-flex items-center">
              <input
                type="checkbox"
                checked={value}
                onChange={() => setFlags({ ...flags, [key]: !value })}
                className="form-checkbox h-5 w-5 text-blue-600"
              />
              <span className="ml-2">{key}</span>
            </label>
          ))}
        </div>
        {error && (
          <div className="p-2 bg-red-100 border border-red-400 text-red-700 rounded">
            エラー: {error}
          </div>
        )}
        {matches.length > 0 && (
          <div className="p-2 bg-green-100 border border-green-400 text-green-700 rounded">
            マッチ数: {matches.length}
            <ul className="list-disc list-inside">
              {matches.map((match, index) => (
                <li key={index}>{match === '' ? '(空文字列)' : match}</li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
};

export default RegexTester;
