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

const comprehensiveMarkdown = `
## 見出し2から始めます

### 見出し3
#### 見出し4

**リスト**

- Hello!
- Hola!
  - Bonjour!
  * Hi!

**番号付きリスト**

1. First
2. Second

**テキストリンク**

[アンカーテキスト](https://example.com)

**画像**

![サンプル画像](https://via.placeholder.com/150)

**画像の横幅を指定する**

![](https://via.placeholder.com/250x100 =200x)

**Altテキストを指定する**

![代替テキスト](https://via.placeholder.com/150)

**キャプションをつける**

![](https://via.placeholder.com/150)
*キャプション*

**画像にリンクを貼る**

[![](https://via.placeholder.com/150)](https://example.com)

**テーブル**

| Head | Head | Head |
| ---- | ---- | ---- |
| Text | Text | Text |
| Text | Text | Text |

**コードブロック**

\`\`\`js
const great = () => {
  console.log("Awesome");
};
\`\`\`

**ファイル名を表示する**

\`\`\`js title="example.js"
const great = () => {
  console.log("Awesome");
};
\`\`\`

**diff のシンタックスハイライト**

\`\`\`diff js
@@ -4,6 +4,5 @@
+    const foo = bar.baz([1, 2, 3]) + 1;
-    let foo = bar.baz([1, 2, 3]);
\`\`\`

**数式**

インラインの数式: $a \\ne 0$

ブロックの数式:

$$
e^{i\\theta} = \\cos\\theta + i\\sin\\theta
$$

**引用**

> 引用文
> 引用文

**脚注**

脚注の例[^1]です。脚注は文末に表示されます。

[^1]: 脚注の内容その1

**区切り線**

-----

**インラインスタイル**

*イタリック* **太字** ~~打ち消し線~~ インラインで\`code\`を挿入する

~~~html
<!-- これはコメントです -->
~~~

### Note

:::note{.warning}
if you chose xxx, you should also use yyy somewhere…
:::
`;

export const ComprehensiveExample: Story = {
  args: {
    content: comprehensiveMarkdown,
  },
};
