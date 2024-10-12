import { Link } from 'react-router-dom';

const ToolCard = ({
  title,
  description,
  link,
}: {
  title: string;
  description: string;
  link: string;
}) => (
  <div className="bg-white dark:bg-night-sky shadow-md rounded-lg p-6 mb-4 border">
    <h3 className="text-xl font-bold mb-2">{title}</h3>
    <p className="text-gray-600 dark:text-gray-400 mb-4">{description}</p>
    <Link
      to={link}
      className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors"
    >
      ツールを使用する
    </Link>
  </div>
);

const ToolsRootPage = () => {
  const tools = [
    {
      title: 'Base64 エンコーダー/デコーダー',
      description:
        'テキストをBase64形式にエンコードしたり、Base64からデコードしたりするツールです。データの変換や送信時に便利です。',
      link: '/tools/base64',
    },
    {
      title: 'JSON/XML フォーマッター',
      description:
        'JSONやXMLデータを整形し、読みやすく表示するツールです。コメント付きJSONにも対応しています。',
      link: '/tools/format',
    },
    {
      title: '改行文字変換ツール',
      description:
        'テキストの改行文字を変換します。一般的な改行文字の選択やカスタム文字の指定が可能です。',
      link: '/tools/newline-converter',
    },
    {
      title: 'URL エンコーダー/デコーダー',
      description:
        'URLの特殊文字をエンコード/デコードするツールです。Web開発やAPIテストに便利です。',
      link: '/tools/url-encoder-decoder',
    },
    {
      title: 'UNIX タイムスタンプ変換ツール',
      description:
        'UNIXタイムスタンプと人間が読める日時形式を相互に変換します。データ処理やログ解析に役立ちます。',
      link: '/tools/unix-timestamp-converter',
    },
    {
      title: 'カラーコード変換ツール',
      description:
        'HEXカラーコードとRGBカラーコードを相互に変換します。Web設計やUIデザインに便利です。',
      link: '/tools/color-code-converter',
    },
    {
      title: '正規表現テスター',
      description:
        '正規表現パターンをリアルタイムでテストし、マッチング結果を確認できるツールです。パターンの作成や検証、デバッグに役立ちます。',
      link: '/tools/regex-tester',
    },
  ];

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">開発ツール</h1>
      <div className="grid md:grid-cols-2 gap-4">
        {tools.map((tool, index) => (
          <ToolCard key={index} {...tool} />
        ))}
      </div>
    </div>
  );
};

export default ToolsRootPage;
