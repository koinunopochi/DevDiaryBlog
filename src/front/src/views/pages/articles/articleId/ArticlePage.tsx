import React from 'react';
import MarkdownRenderer from '@components/blocks/markdownRenderer/MarkdownRenderer';

interface ArticlePageProps {
  content: string;
  getLinkCardInfo: (
    url: string
  ) => Promise<{ url: string; imageUrl: string; title: string }>;
}

const ArticlePage: React.FC<ArticlePageProps> = ({
  content,
  getLinkCardInfo,
}) => {
  return (
    <div className="container mx-auto px-4 py-8">
      <article className="prose lg:prose-xl">
        <MarkdownRenderer
          content={content}
          getLinkCardInfo={getLinkCardInfo}
          className=""
        />
      </article>
    </div>
  );
};

export default ArticlePage;
