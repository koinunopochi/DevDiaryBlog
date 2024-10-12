import React, { useState } from 'react';
import { Search, Calendar, User, Tag, ThumbsUp, MessageCircle } from 'lucide-react';

const ArticleRootPage = () => {
  const [selectedCategory, setSelectedCategory] = useState('全て');

  const categories = [
    '全て',
    'Docker',
    'Laravel',
    'React',
    'MySQL',
    'Tailwind',
  ];

  const articles = [
    {
      id: 1,
      title: 'Docker Composeで実現する超快適な開発環境',
      date: '2024-10-05',
      author: '山田太郎',
      excerpt:
        'Docker Composeを駆使して、Laravel、MySQL、Reactの開発環境を爆速で構築する方法を徹底解説。環境構築の悩みから解放されよう！',
      category: 'Docker',
      likes: 124,
      comments: 18,
      image: '/api/placeholder/800/600',
    },
    {
      id: 2,
      title: 'LaravelとReactの匠の技：最強のフルスタック開発',
      date: '2024-10-08',
      author: '佐藤花子',
      excerpt:
        'LaravelバックエンドとReact SPAフロントエンドの連携テクニックを完全網羅。パフォーマンスと開発効率を両立する秘訣を公開！',
      category: 'Laravel',
      likes: 89,
      comments: 23,
      image: '/api/placeholder/800/600',
    },
    {
      id: 3,
      title: 'Tailwind CSSで魅せる！レスポンシブの神業テクニック',
      date: '2024-10-10',
      author: '鈴木一郎',
      excerpt:
        'Tailwind CSSを使いこなし、あらゆるデバイスで完璧に決まるレスポンシブデザインを実現。コード量削減と美しさの両立を体感せよ。',
      category: 'Tailwind',
      likes: 156,
      comments: 31,
      image: '/api/placeholder/800/600',
    },
  ];

  const filteredArticles =
    selectedCategory === '全て'
      ? articles
      : articles.filter((article) => article.category === selectedCategory);

  return (
    <div className="">
      <div className="container mx-auto px-4 py-8">
        {/* <h1 className="text-4xl font-bold mb-8 text-center">テックブログ</h1> */}

        <div className="mb-8">
          <div className="relative max-w-xl mx-auto">
            <input
              type="text"
              placeholder="記事を検索..."
              className="w-full p-3 pl-12 border rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
            />
            <Search
              className="absolute left-4 top-3.5 text-gray-400"
              size={20}
            />
          </div>
        </div>

        <div className="flex flex-wrap justify-center gap-2 mb-8">
          {categories.map((category) => (
            <button
              key={category}
              onClick={() => setSelectedCategory(category)}
              className={`px-4 py-2 rounded-full ${
                selectedCategory === category
                  ? 'bg-blue-500 text-white'
                  : 'bg-white text-gray-700 hover:bg-gray-100'
              } transition-colors duration-200`}
            >
              {category}
            </button>
          ))}
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {filteredArticles.map((article) => (
            <div
              key={article.id}
              className="bg-white rounded-lg overflow-hidden shadow-lg transition-transform duration-300 hover:scale-105"
            >
              <img
                src={article.image}
                alt={article.title}
                className="w-full h-48 object-cover"
              />
              <div className="p-6">
                <div className="flex items-center mb-2">
                  <Tag size={16} className="mr-2 text-blue-500" />
                  <span className="text-sm text-blue-500">
                    {article.category}
                  </span>
                </div>
                <h2 className="text-xl font-semibold mb-2 line-clamp-2">
                  {article.title}
                </h2>
                <p className="text-gray-600 mb-4 line-clamp-3">
                  {article.excerpt}
                </p>
                <div className="flex items-center text-sm text-gray-500 mb-4">
                  <Calendar size={16} className="mr-1" />
                  <span className="mr-4">{article.date}</span>
                  <User size={16} className="mr-1" />
                  <span>{article.author}</span>
                </div>
                <div className="flex items-center justify-between">
                  <div className="flex items-center text-gray-500">
                    <ThumbsUp size={16} className="mr-1" />
                    <span className="mr-3">{article.likes}</span>
                    <MessageCircle size={16} className="mr-1" />
                    <span>{article.comments}</span>
                  </div>
                  <button className="text-blue-500 hover:text-blue-700 font-semibold">
                    続きを読む
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-12 flex justify-center">
          <button className="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition-colors duration-200 shadow-md">
            もっと記事を読む
          </button>
        </div>
      </div>
    </div>
  );
};

export default ArticleRootPage;
