/* eslint-disable @typescript-eslint/no-unused-vars */
import React from 'react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { oneDark } from 'react-syntax-highlighter/dist/esm/styles/prism';

interface MarkdownRendererProps {
  content: string;
  className?: string;
}

const MarkdownRenderer: React.FC<MarkdownRendererProps> = ({
  content,
  className = '',
}) => {
  return (
    <div className={`markdown-body ${className}`}>
      <ReactMarkdown
        remarkPlugins={[remarkGfm]}
        components={{
          code({ node, inline, className, children, ...props }) {
            const match = /language-(\w+)/.exec(className || '');
            return !inline && match ? (
              <SyntaxHighlighter
                style={oneDark}
                language={match[1]}
                PreTag="div"
                {...props}
                className="rounded-md"
              >
                {String(children).replace(/\n$/, '')}
              </SyntaxHighlighter>
            ) : (
              <code
                className={`${className} px-1 py-0.5 rounded-sm bg-gray-100 dark:bg-gray-800`}
                {...props}
              >
                {children}
              </code>
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
          p: ({ node, ...props }) => (
            <p className="my-2 leading-7" {...props} />
          ),
          ul: ({ node, ...props }) => (
            <ul className="list-disc list-inside my-3" {...props} />
          ),
          ol: ({ node, ...props }) => (
            <ol className="list-decimal list-inside my-3" {...props} />
          ),
          li: ({ node, ...props }) => <li className="my-1" {...props} />,
          a: ({ node, ...props }) => (
            <a
              className="text-blue-600 hover:underline dark:text-blue-400"
              {...props}
            />
          ),
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
        }}
      >
        {content}
      </ReactMarkdown>
    </div>
  );
};

export default MarkdownRenderer;
