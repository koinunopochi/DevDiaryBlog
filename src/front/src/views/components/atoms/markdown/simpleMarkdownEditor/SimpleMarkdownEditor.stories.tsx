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
    onImageUpload: { action: 'onImageUpload' },
    onUnusedImagesDetected: { action: 'onUnusedImagesDetected' },
  },
};

export default meta;
type Story = StoryObj<typeof meta>;

// モックの画像アップロード関数
const mockImageUpload = async (file: File): Promise<string> => {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(`https://example.com/images/${file.name}`);
    }, 1000);
  });
};

export const Default: Story = {
  args: {
    onImageUpload: mockImageUpload,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const WithInitialValue: Story = {
  args: {
    value:
      '# Welcome to SimpleMarkdownEditor\n\nThis is a **simple** markdown editor.',
    onImageUpload: mockImageUpload,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const WithCustomOnChange: Story = {
  args: {
    onChange: (value: string | undefined) => {
      console.log(`New value: ${value}`);
    },
    onImageUpload: mockImageUpload,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const WithLongContent: Story = {
  args: {
    value: '# Long Content\n\n' + 'Lorem ipsum '.repeat(100),
    onImageUpload: mockImageUpload,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const WithCodeBlock: Story = {
  args: {
    value:
      '# Code Block Example\n\n```javascript\nconst greeting = "Hello, world!";\nconsole.log(greeting);\n```',
    onImageUpload: mockImageUpload,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const WithImagePlaceholder: Story = {
  args: {
    value: '# Image Example\n\n![Example Image](https://example.com/image.jpg)',
    onImageUpload: mockImageUpload,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const WithCustomImageUpload: Story = {
  args: {
    onImageUpload: async (file: File) => {
      console.log(`Uploading file: ${file.name}`);
      await new Promise((resolve) => setTimeout(resolve, 2000)); // 2秒の遅延をシミュレート
      return `https://custom-example.com/images/${file.name}`;
    },
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};
