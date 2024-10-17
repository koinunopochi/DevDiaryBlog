import { Link } from 'react-router-dom';

const DevelopRootPage = () => {
  const sections = [
    {
      title: 'デザインシステム',
      items: [
        { name: 'カラーパレット', path: '/develop/design/colors' },
        { name: 'タイポグラフィ', path: '/develop/design/typography' },
        { name: 'コンポーネント', path: '/develop/design/components' },
      ],
    },
    {
      title: 'ドキュメント',
      items: [
        { name: 'API仕様', path: '/develop/docs/api' },
        { name: 'フロントエンド構造', path: '/develop/docs/frontend' },
        { name: 'バックエンド構造', path: '/develop/docs/backend' },
      ],
    },
    {
      title: '開発ツール',
      items: [
        { name: 'ステージング環境', path: '/develop/tools/staging' },
        { name: 'テストスイート', path: '/develop/tools/tests' },
        { name: 'CI/CDパイプライン', path: '/develop/tools/cicd' },
      ],
    },
  ];

  return (
    <div className="bg-[#fff5e6] min-h-screen p-8">
      <h1 className="text-4xl font-bold text-[#333333] mb-8">
        tetex.tech 開発ハブ
      </h1>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {sections.map((section, index) => (
          <div key={index} className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="text-2xl font-semibold text-[#ffa500] mb-4">
              {section.title}
            </h2>
            <ul className="space-y-2">
              {section.items.map((item, itemIndex) => (
                <li key={itemIndex}>
                  <Link
                    to={item.path}
                    className="text-[#e69400] hover:text-[#ffa500] transition-colors duration-200"
                  >
                    {item.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        ))}
      </div>

      <footer className="mt-12 text-center text-[#666666]">
        <p>© 2024 tetex.tech All Rights Reserved.</p>
      </footer>
    </div>
  );
};

export default DevelopRootPage;
