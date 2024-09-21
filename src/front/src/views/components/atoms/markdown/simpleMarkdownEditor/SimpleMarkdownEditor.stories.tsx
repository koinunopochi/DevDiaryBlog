import type { Meta, StoryObj } from '@storybook/react';
import SimpleMarkdownEditor from './SimpleMarkdownEditor';

const meta: Meta<typeof SimpleMarkdownEditor> = {
  title: 'components/SimpleMarkdownEditor',
  component: SimpleMarkdownEditor,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    value: { control: 'text' },
    onChange: { action: 'onChange' },
  },
};

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {},
};

export const WithInitialValue: Story = {
  args: {
    value: '# Welcome to SimpleMarkdownEditor\n\nThis is a **simple** markdown editor.',
  },
};

export const WithCustomOnChange: Story = {
  args: {
    onChange: (value: string | undefined) => {
      console.log(`New value: ${value}`);
    },
  },
};

export const WithPreviewEnabled: Story = {
  args: {
    value: '# Preview Mode\n\nThis story starts with preview mode enabled.',
    previewEnabled: true,
  },
};

export const WithLongContent: Story = {
  args: {
    value: '# Long Content\n\n' + 'Lorem ipsum '.repeat(100),
  },
};

export const WithCodeBlock: Story = {
  args: {
    value: '# Code Block Example\n\n```javascript\nconst greeting = "Hello, world!";\nconsole.log(greeting);\n```',
  },
};

export const WithImagePlaceholder: Story = {
  args: {
    value: '# Image Example\n\n![Example Image](https://example.com/image.jpg)',
  },
};
