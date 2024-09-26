/* eslint-disable @typescript-eslint/no-explicit-any */
import React from 'react';

interface MarkdownListItemProps {
  children: React.ReactNode;
  [key: string]: any;
}

const MarkdownListItem: React.FC<MarkdownListItemProps> = ({
  children,
  ...props
}) => {
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
            return React.cloneElement(child as React.ReactElement<any>, {
              className: 'mt-2 space-y-2',
            });
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
};

export default MarkdownListItem;
