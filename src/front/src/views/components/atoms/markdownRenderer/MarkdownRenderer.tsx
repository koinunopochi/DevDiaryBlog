/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable @typescript-eslint/no-unused-vars */
import React from 'react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import remarkMath from 'remark-math';
import remarkCodeTitle from 'remark-code-title';
import rehypeKatex from 'rehype-katex';
import remarkDirective from 'remark-directive';
import rehypeRaw from 'rehype-raw';
import { visit } from 'unist-util-visit';
import { h } from 'hastscript';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { oneDark } from 'react-syntax-highlighter/dist/esm/styles/prism';
import type { SyntaxHighlighterProps } from 'react-syntax-highlighter';
import { Info, AlertTriangle, AlertOctagon } from 'lucide-react';

import 'katex/dist/katex.min.css';

interface MarkdownRendererProps {
  content: string;
  className?: string;
}

const remarkCustomNotesPlugin = () => {
  return (tree: any) => {
    visit(tree, (node) => {
      if (
        node.type === 'containerDirective' ||
        node.type === 'leafDirective' ||
        node.type === 'textDirective'
      ) {
        // シンタックスシュガーの変換
        const nameMap: { [key: string]: string } = {
          I: 'info',
          W: 'warn',
          A: 'alert',
        };

        if (node.name in nameMap) {
          node.name = nameMap[node.name];
        }

        if (!['info', 'warn', 'alert'].includes(node.name)) return;

        const data = node.data || (node.data = {});
        const tagName = node.type === 'textDirective' ? 'span' : 'div';

        let className = 'custom-note';
        className += ` ${node.name}`;

        data.hName = tagName;
        data.hProperties = h(tagName, { className }).properties;
      }
    });
  };
};

interface CustomNoteProps extends React.HTMLAttributes<HTMLDivElement> {
  className?: string;
}

const CustomNote: React.FC<CustomNoteProps> = ({
  className = '',
  children,
  ...props
}) => {
  let Icon = Info;
  if (className.includes('warn')) Icon = AlertTriangle;
  if (className.includes('alert')) Icon = AlertOctagon;

  return (
    <div
      className={`custom-note ${className} flex items-start p-4 my-4 rounded-md`}
      {...props}
    >
      <div className="flex-shrink-0 self-center mr-3">
        <Icon className="w-5 h-5" />
      </div>
      <div className="flex-grow">{children}</div>
    </div>
  );
};

const MarkdownRenderer: React.FC<MarkdownRendererProps> = ({
  content,
  className = '',
}) => {
  return (
    <div className={`markdown-body ${className}`}>
      <ReactMarkdown
        remarkPlugins={[
          remarkGfm,
          remarkMath,
          remarkCodeTitle,
          remarkDirective,
          remarkCustomNotesPlugin,
        ]}
        rehypePlugins={[rehypeKatex, rehypeRaw]}
        components={{
          code({ node, className, children, ...props }) {
            const match = /language-(\w+)/.exec(className || '');
            return match ? (
              <SyntaxHighlighter
                {...(props as SyntaxHighlighterProps)}
                style={oneDark}
                language={match[1]}
                PreTag="div"
                className="rounded-md !mt-0"
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
            <ul className="list-disc pl-5 space-y-2 my-4" {...props} />
          ),
          ol: ({ node, ...props }) => (
            <ol className="list-decimal pl-5 space-y-2 my-4" {...props} />
          ),
          li: ({ node, children, ...props }) => {
            if (
              React.Children.toArray(children).some(
                (child) =>
                  React.isValidElement(child) &&
                  (child.type === 'ul' || child.type === 'ol')
              )
            ) {
              return (
                <li className="mb-2" {...props}>
                  {React.Children.map(children, (child) => {
                    if (
                      React.isValidElement(child) &&
                      (child.type === 'ul' || child.type === 'ol')
                    ) {
                      return React.cloneElement(
                        child as React.ReactElement<any>,
                        {
                          className: 'mt-2 space-y-2',
                        }
                      );
                    }
                    return child;
                  })}
                </li>
              );
            }
            return (
              <li className="mb-2" {...props}>
                {children}
              </li>
            );
          },
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

export default MarkdownRenderer;
