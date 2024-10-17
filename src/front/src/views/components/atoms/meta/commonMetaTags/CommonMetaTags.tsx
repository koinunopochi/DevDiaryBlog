import React from 'react';
import { Helmet } from 'react-helmet';

const CommonMetaTags: React.FC = () => {
  return (
    <Helmet>
      <meta name="theme-color" content="#FFA500" />
      {/* <link rel="apple-touch-icon" href="/logo192.png" /> */}
      {/* <link rel="manifest" href="/manifest.json" /> */}

      {/* 共通のメタタグ */}
      <meta
        name="description"
        content="tetex.techは、技術（Technology）と文章（Text）を融合させ、複雑な技術的概念をわかりやすく説明することを目指すプラットフォームです。最新のWeb技術を駆使して、技術文書の作成と共有を革新します。"
      />
      <meta
        name="keywords"
        content="技術文書, テックライティング, 説明技術, ウェブ開発, テクノロジー, 文章作成, 知識共有, プログラミング, LaTeX, Markdown"
      />
      <meta name="author" content="koinunopochi" />

      {/* OGP共通タグ */}
      <meta property="og:site_name" content="tetex.tech" />
      <meta property="og:type" content="website" />
      <meta property="og:locale" content="ja_JP" />

      {/* Twitter Card */}
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:site" content="@a1a2a3b1b2b3b4" />
    </Helmet>
  );
};

export default CommonMetaTags;
