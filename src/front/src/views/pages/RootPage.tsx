import { Newspaper, Flame, Tag, ArrowRight } from 'lucide-react';

const RootPage = () => {
  const featuredPost = {
    title: '2024年のWeb開発トレンド：DockerからAIまで',
    excerpt:
      '最新のテクノロジーとベストプラクティスを網羅。今年のWeb開発シーンを完全解説！',
    image: '/api/placeholder/1200/600',
    author: '山田太郎',
    date: '2024-10-01',
  };

  const recentPosts = [
    {
      id: 1,
      title: 'LaravelとReactの最適な連携方法',
      category: '開発テクニック',
    },
    { id: 2, title: 'DockerComposeで環境構築を効率化', category: 'Docker' },
    {
      id: 3,
      title: 'Tailwind CSSで美しいUIを素早く構築',
      category: 'フロントエンド',
    },
    {
      id: 4,
      title: 'MySQLパフォーマンスチューニングの極意',
      category: 'データベース',
    },
  ];

  const popularPosts = [
    { id: 1, title: '初心者でもわかる！Reactフックの完全ガイド', views: 15420 },
    { id: 2, title: 'Laravel9の新機能総まとめ', views: 12980 },
    { id: 3, title: 'Docker入門：概念から実践まで', views: 10750 },
  ];

  const categories = [
    { name: 'フロントエンド', count: 45 },
    { name: 'バックエンド', count: 38 },
    { name: 'DevOps', count: 22 },
    { name: 'データベース', count: 19 },
    { name: 'セキュリティ', count: 15 },
  ];

  return (
    <div className="">
        <section className="mb-16">
          <h2 className="text-3xl font-bold mb-8">注目の記事</h2>
          <div className="bg-white rounded-lg overflow-hidden shadow-lg">
            <img
              src={featuredPost.image}
              alt={featuredPost.title}
              className="w-full h-64 object-cover"
            />
            <div className="p-6">
              <h3 className="text-2xl font-semibold mb-2">
                {featuredPost.title}
              </h3>
              <p className="text-gray-600 mb-4">{featuredPost.excerpt}</p>
              <div className="flex justify-between items-center text-sm text-gray-500">
                <span>{featuredPost.author}</span>
                <span>{featuredPost.date}</span>
              </div>
            </div>
          </div>
        </section>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <section className="md:col-span-2">
            <h2 className="text-2xl font-bold mb-6 flex items-center">
              <Newspaper className="mr-2" /> 最新の記事
            </h2>
            <div className="space-y-4">
              {recentPosts.map((post) => (
                <div key={post.id} className="bg-white p-4 rounded-lg shadow">
                  <h3 className="font-semibold mb-1">{post.title}</h3>
                  <span className="text-sm text-blue-500">{post.category}</span>
                </div>
              ))}
            </div>
            <button className="mt-6 text-blue-500 font-semibold flex items-center hover:text-blue-700">
              すべての記事を見る <ArrowRight className="ml-1" size={18} />
            </button>
          </section>

          <section>
            <h2 className="text-2xl font-bold mb-6 flex items-center">
              <Flame className="mr-2" /> 人気の記事
            </h2>
            <div className="space-y-4">
              {popularPosts.map((post) => (
                <div key={post.id} className="bg-white p-4 rounded-lg shadow">
                  <h3 className="font-semibold mb-1">{post.title}</h3>
                  <span className="text-sm text-gray-500">
                    {post.views.toLocaleString()} views
                  </span>
                </div>
              ))}
            </div>
          </section>
        </div>

        <section className="mt-16">
          <h2 className="text-2xl font-bold mb-6 flex items-center">
            <Tag className="mr-2" /> カテゴリー
          </h2>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            {categories.map((category) => (
              <div
                key={category.name}
                className="bg-white p-4 rounded-lg shadow text-center"
              >
                <h3 className="font-semibold mb-1">{category.name}</h3>
                <span className="text-sm text-gray-500">
                  {category.count} 記事
                </span>
              </div>
            ))}
          </div>
        </section>
    </div>
  );
};

export default RootPage;
