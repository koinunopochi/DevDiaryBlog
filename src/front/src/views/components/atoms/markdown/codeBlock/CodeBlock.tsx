import React from 'react';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { oneDark } from 'react-syntax-highlighter/dist/esm/styles/prism';
import type { SyntaxHighlighterProps } from 'react-syntax-highlighter';

interface CodeBlockProps {
  className?: string;
  children: React.ReactNode;
}

const CodeBlock: React.FC<CodeBlockProps> = ({
  className,
  children,
  ...props
}) => {
  const match = /language-(\w+)/.exec(className || '');
  return match ? (
    <SyntaxHighlighter
      style={oneDark}
      language={match[1]}
      PreTag="div"
      className="rounded-md !mt-0"
      {...(props as SyntaxHighlighterProps)}
    >
      {String(children).replace(/\n$/, '')}
    </SyntaxHighlighter>
  ) : (
    <code
      className={`${className} px-1 py-0.5 rounded-sm bg-gray-100 dark:bg-gray-800`}
    >
      {children}
    </code>
  );
};

export default CodeBlock;
