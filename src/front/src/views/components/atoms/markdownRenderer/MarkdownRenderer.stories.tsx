import type { Meta, StoryObj } from '@storybook/react';
import MarkdownRenderer from './MarkdownRenderer';

const meta: Meta<typeof MarkdownRenderer> = {
  title: 'atoms/MarkdownRenderer',
  component: MarkdownRenderer,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    content: { control: 'text' },
    className: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof MarkdownRenderer>;

export const Default: Story = {
  args: {
    content: '# Hello\nThis is a **bold** text.',
  },
};

export const WithLongContent: Story = {
  args: {
    content: `
# Long Markdown Content

## Introduction
This is a longer piece of Markdown content to demonstrate how the MarkdownRenderer handles more complex structures.

## Features
- Lists
- **Bold text**
- *Italic text*
- [Links](https://example.com)

### Code Block

#### 一つ目の書き方
\`\`\`javascript
function hello() {
  console.log('Hello, world!');
}
\`\`\`

#### 二つ目の書き方

~~~js
function hello() {
  console.log('Hello, world!');
}
~~~

## Conclusion
This demonstrates various Markdown features.
    `,
  },
};

export const WithCustomClass: Story = {
  args: {
    content: '# Styled Markdown\nThis has a custom class.',
    className: 'bg-gray-100 p-4 rounded',
  },
};

export const WithTable: Story = {
  args: {
    content: `
| Header 1 | Header 2 |
|----------|----------|
| Cell 1   | Cell 2   |
| Cell 3   | Cell 4   |
    `,
  },
};

export const WithBlockquote: Story = {
  args: {
    content: '> This is a blockquote.\n\nNormal text here.',
  },
};

export const EmptyContent: Story = {
  args: {
    content: '',
  },
};
