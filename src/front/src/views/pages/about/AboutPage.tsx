import { Github, Linkedin, Mail, Twitter } from 'lucide-react';
import React from 'react';
import unknownIcon from '@/img/unknown-user.png';

interface BlogInfo {
  blogName: string;
  blogDescription: string;
  authorName: string;
  authorBio: string;
  authorImgUrl: string;
  topics: string[];
  socialLinks: { platform: string; url: string }[];
}

const defaultBlogInfo: BlogInfo = {
  blogName: 'DevDiaryBlog',
  blogDescription:
    'DevDiaryBlogは、開発者の日々の経験、学び、そして挑戦を共有するプラットフォームです。',
  authorName: 'DevDiary管理人',
  authorBio:
    '技術と創造性の融合に情熱を注ぐ開発者です。日々の発見と洞察を皆さんと共有したいと思っています。',
  authorImgUrl: unknownIcon,
  topics: [
    'ウェブ開発',
    'プログラミング言語',
    '開発ツール',
    'ベストプラクティス',
    'テクノロジートレンド',
  ],
  socialLinks: [
    { platform: 'GitHub', url: 'https://github.com/koinunopochi/DevDiaryBlog' },
  ],
};

const AboutPage: React.FC<{ blogInfo?: Partial<BlogInfo> }> = ({
  blogInfo = {},
}) => {
  const {
    blogName,
    blogDescription,
    authorName,
    authorBio,
    authorImgUrl,
    topics,
    socialLinks,
  } = { ...defaultBlogInfo, ...blogInfo };

  return (
    <div className="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8 bg-gray-50 dark:bg-night-sky rounded-lg border">
      <h1 className="text-4xl font-extrabold text-indigo-600 mb-6">
        {blogName}について
      </h1>

      <section className="mb-12 bg-white dark:bg-night-sky rounded-lg p-6 border">
        <h2 className="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
          ブログの紹介
        </h2>
        <p className="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
          {blogDescription}
        </p>
      </section>

      <section className="mb-12 bg-white dark:bg-night-sky rounded-lg p-6 border">
        <h2 className="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
          運営紹介
        </h2>
        <div className="flex items-center mb-4">
          <img
            src={authorImgUrl}
            alt={authorName}
            className="w-20 h-20 rounded-full mr-4"
          />
          <div>
            <h3 className="text-xl font-medium text-gray-900 dark:text-gray-200">
              {authorName}
            </h3>
          </div>
        </div>
        <p className="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
          {authorBio}
        </p>
      </section>

      <section className="mb-12 bg-white dark:bg-night-sky rounded-lg p-6 border">
        <h2 className="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
          主なトピック
        </h2>
        <div className="grid grid-cols-2 gap-4">
          {topics.map((topic, index) => (
            <div
              key={index}
              className="bg-indigo-100 dark:bg-night-sky rounded-lg p-1 flex items-center justify-center border"
            >
              <p className="text-indigo-700 font-medium mt-3 text-center">
                {topic}
              </p>
            </div>
          ))}
        </div>
      </section>

      <section className="bg-white dark:bg-night-sky rounded-lg p-6 border">
        <h2 className="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
          お問い合わせ・フォロー
        </h2>
        <p className="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
          ご質問、フィードバック、またはコラボレーションのご提案がありましたら、以下のSNSでお気軽にお問い合わせください。新しいアイデアや技術的な議論を歓迎します！
        </p>
        <div className="flex space-x-4 mt-4">
          {socialLinks.map((link, index) => (
            <a
              key={index}
              href={link.url}
              target="_blank"
              rel="noopener noreferrer"
              className="text-gray-400 hover:text-indigo-500 transition-colors duration-200"
            >
              <span className="sr-only">{link.platform}</span>
              {link.platform === 'Twitter' && <Twitter className="h-6 w-6" />}
              {link.platform === 'GitHub' && <Github className="h-6 w-6" />}
              {link.platform === 'LinkedIn' && <Linkedin className="h-6 w-6" />}
              {link.platform === 'Email' && <Mail className="h-6 w-6" />}
            </a>
          ))}
        </div>
      </section>
    </div>
  );
};

export default AboutPage;
