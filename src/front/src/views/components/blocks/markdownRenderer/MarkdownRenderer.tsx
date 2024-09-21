/* eslint-disable @typescript-eslint/no-unused-vars */
import React from 'react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import remarkMath from 'remark-math';
import remarkCodeTitle from 'remark-code-title';
import rehypeKatex from 'rehype-katex';
import remarkDirective from 'remark-directive';
import rehypeRaw from 'rehype-raw';
import CustomNote from '@components/atoms/customNote/CustomNote';
import LinkCard from '@components/atoms/linkCard/LinkCard';

import 'katex/dist/katex.min.css';
import remarkCustomNotes from '@/infrastructure/remarkPlugins/remarkCustomNotes';
import MarkdownListItem from '@components/atoms/markdown/markdownListItem/MarkdownListItem';
import CodeBlock from '@components/atoms/markdown/codeBlock/CodeBlock';

interface MarkdownRendererProps {
  content: string;
  className?: string;
  getLinkCardInfo: (
    url: string
  ) => Promise<{ url: string; imageUrl: string; title: string }>;
}

const MarkdownRenderer: React.FC<MarkdownRendererProps> = ({
  content,
  className = '',
  getLinkCardInfo,
}) => {
  return (
    <div className={`markdown-body ${className}`}>
      <ReactMarkdown
        remarkPlugins={[
          remarkGfm,
          remarkMath,
          remarkCodeTitle,
          remarkDirective,
          remarkCustomNotes,
        ]}
        rehypePlugins={[rehypeKatex, rehypeRaw]}
        components={{
          code: ({ node, className, children, ...props }) => {
            return (
              <CodeBlock children={children} className={className} {...props} />
            );
          },
          h1: ({ node, ...props }) => (
            <h1 className="text-3xl font-bold mt-6 mb-4" {...props} />
          ),
          h2: ({ node, ...props }) => (
            <h2 className="text-2xl font-semibold mt-5 mb-3" {...props} />
          ),
          h3: ({ node, ...props }) => (
            <h3 className="text-xl font-medium mt-4 mb-2" {...props} />
          ),
          p: ({ node, children, ...props }) => {
            // Check if the paragraph contains only a single link
            if (
              node?.children.length === 1 &&
              node.children[0].type === 'element' &&
              node.children[0].tagName === 'a' &&
              node.children[0].children.length === 1 &&
              node.children[0].children[0].type === 'text' &&
              node.children[0].children[0].value ===
                node.children[0].properties.href
            ) {
              const href = node.children[0].properties.href as string;
              if (href && href.match(/^https?:\/\//)) {
                return (
                  <LinkCardWrapper
                    href={href}
                    getLinkCardInfo={getLinkCardInfo}
                  >
                    {children}
                  </LinkCardWrapper>
                );
              }
            }
            return (
              <p className="my-2 leading-7" {...props}>
                {children}
              </p>
            );
          },
          ul: ({ node, ...props }) => (
            <ul className="list-disc pl-5 space-y-2 my-4" {...props} />
          ),
          ol: ({ node, ...props }) => (
            <ol className="list-decimal pl-5 space-y-2 my-4" {...props} />
          ),
          li: ({ node, children, ...props }) => {
            return <MarkdownListItem children={children} {...props} />;
          },
          a: ({ node, href, children, ...props }) => {
            return (
              <a
                className="text-blue-600 hover:underline dark:text-blue-400"
                target="_blank"
                rel="noopener noreferrer"
                href={href}
                {...props}
              >
                {children}
              </a>
            );
          },
          blockquote: ({ node, ...props }) => (
            <blockquote
              className="border-l-4 border-gray-300 dark:border-gray-700 pl-4 my-3 italic"
              {...props}
            />
          ),
          table: ({ node, ...props }) => (
            <div className="overflow-x-auto my-4">
              <table
                className="min-w-full divide-y divide-gray-300 dark:divide-gray-700"
                {...props}
              />
            </div>
          ),
          th: ({ node, ...props }) => (
            <th
              className="px-3 py-2 bg-gray-100 dark:bg-gray-800 font-semibold text-left"
              {...props}
            />
          ),
          td: ({ node, ...props }) => (
            <td
              className="px-3 py-2 border-t border-gray-200 dark:border-gray-800"
              {...props}
            />
          ),
          div: ({ node, ...props }) => {
            if (props.className && props.className.includes('custom-note')) {
              return <CustomNote {...props} />;
            }
            return <div {...props} />;
          },
        }}
      >
        {content}
      </ReactMarkdown>
    </div>
  );
};

const LinkCardWrapper: React.FC<{
  href: string;
  getLinkCardInfo: (
    url: string
  ) => Promise<{ url: string; imageUrl: string; title: string }>;
  children: React.ReactNode;
}> = ({ href, getLinkCardInfo, children }) => {
  const [linkInfo, setLinkInfo] = React.useState<{
    url: string;
    imageUrl: string;
    title: string;
  } | null>(null);

  React.useEffect(() => {
    getLinkCardInfo(href).then(setLinkInfo);
  }, [href, getLinkCardInfo]);

  if (linkInfo) {
    return <LinkCard {...linkInfo} />;
  }

  return (
    <a className="text-blue-600 hover:underline dark:text-blue-400" href={href}>
      {children}
    </a>
  );
};

export default MarkdownRenderer;
