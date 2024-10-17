import { Link } from 'react-router-dom';

const ToolCard = ({
  title,
  description,
  link,
  isExternal = false,
}: {
  title: string;
  description: string;
  link: string;
  isExternal?: boolean;
}) => (
  <div className="shadow-md rounded-lg p-6 mb-4 border">
    <h3 className="text-xl font-bold mb-2">{title}</h3>
    <p className="mb-4">{description}</p>
    {isExternal ? (
      <a
        href={link}
        target="_blank"
        rel="noopener noreferrer"
        className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base inline-block text-center"
      >
        ツールを使用する
      </a>
    ) : (
      <Link
        to={link}
        className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base inline-block text-center"
      >
        ツールを使用する
      </Link>
    )}
  </div>
);
const ToolsRootPage = () => {
  const internalTools = [
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

  const externalTools = {
    コーディング支援: [
      {
        title: '変数名生成ツール',
        description: 'プログラミングの変数名を簡単に考えられるツールです。',
        link: 'https://codic.jp/engine',
      },
      {
        title: 'JSON Formatter & Validator',
        description:
          'JSONデータのフォーマットと検証を行うオンラインツールです。',
        link: 'https://jsonformatter.curiousconcept.com/',
      },
      {
        title: 'Regex101',
        description: '正規表現のテストと解説を行うツールです。',
        link: 'https://regex101.com/',
      },
    ],
    デザイン: [
      {
        title: 'イラスト素材',
        description: 'シンプルで使いやすいイラスト素材を提供しています。',
        link: 'https://loosedrawing.com/illust/1460/',
      },
      {
        title: 'カラーパレット生成',
        description: '色の組み合わせを簡単に見つけられるツールです。',
        link: 'https://coolors.co/000000-291528-3a3e3b-f0eff4-9e829c',
      },
      {
        title: 'Googleアイコン',
        description: 'Googleが提供する豊富なアイコンセットです。',
        link: 'https://fonts.google.com/icons',
      },
      {
        title: 'フリーアイコン集',
        description: '多様なフリーアイコンを提供しています。',
        link: 'https://icooon-mono.com/category/other/page/20/',
      },
      {
        title: 'CSS Grid Generator',
        description: 'CSSグリッドレイアウトを視覚的に作成できるツールです。',
        link: 'https://cssgrid-generator.netlify.app/',
      },
      {
        title: 'Favicon Generator',
        description: '様々なサイズのファビコンを生成するツールです。',
        link: 'https://realfavicongenerator.net/',
      },
    ],
    ユーティリティ: [
      {
        title: 'AWSコスト計算',
        description: 'AWSサービスのコストを簡単に計算できます。',
        link: 'https://aws-rough.cc/',
      },
      {
        title: '絵文字コピーツール',
        description: '様々な絵文字をコピーして使用できます。',
        link: 'https://emojigraph.org/ja/party-popper/',
      },
      {
        title: 'Lorem Ipsum Generator',
        description: 'ダミーテキストを生成するツールです。',
        link: 'https://loremipsum.io/',
      },
      {
        title: 'Optimize Images',
        description: '画像を最適化して圧縮するツールです。',
        link: 'https://imagecompressor.com/',
      },
    ],
    参考資料: [
      {
        title: 'サービスロゴまとめ',
        description: '様々なサービスのロゴをまとめて確認できます。',
        link: 'https://qiita.com/Ry0xi/items/857687c892f783955fe1#python%E5%85%AC%E5%BC%8F',
      },
      {
        title: 'Can I Use',
        description: 'ウェブ技術の互換性をブラウザごとに確認できるツールです。',
        link: 'https://caniuse.com/',
      },
      {
        title: 'Public APIs',
        description: '無料で使用できるAPIのリストです。',
        link: 'https://github.com/public-apis/public-apis',
      },
      {
        title: 'DevDocs',
        description:
          '多数のプログラミング言語やフレームワークのドキュメントを一箇所で検索できるツールです。',
        link: 'https://devdocs.io/',
      },
    ],
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">開発ツール</h1>

      <h2 className="text-2xl font-bold mb-4">内部ツール</h2>
      <div className="grid md:grid-cols-2 gap-4 mb-8">
        {internalTools.map((tool, index) => (
          <ToolCard key={index} {...tool} />
        ))}
      </div>

      <h2 className="text-2xl font-bold mb-4">外部おすすめツール・サイト</h2>
      <p className='text-red-400'>※ 安全性を保障するものではないことに注意してください。</p>
      {Object.entries(externalTools).map(([category, tools]) => (
        <div key={category} className="mb-8">
          <h3 className="text-xl font-bold mb-4">{category}</h3>
          <div className="grid md:grid-cols-2 gap-4">
            {tools.map((tool, index) => (
              <ToolCard key={index} {...tool} isExternal={true} />
            ))}
          </div>
        </div>
      ))}
    </div>
  );
};

export default ToolsRootPage;
