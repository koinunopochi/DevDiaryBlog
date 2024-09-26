import type { Meta, StoryObj } from '@storybook/react';
import PreviewMarkdownEditor from './PreviewMarkdownEditor';

const meta: Meta<typeof PreviewMarkdownEditor> = {
  title: 'components/PreviewMarkdownEditor',
  component: PreviewMarkdownEditor,
  parameters: {
    layout: 'fullscreen',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onChange: { action: 'onChange' },
    onImageUpload: { action: 'onImageUpload' },
    onUnusedImagesDetected: { action: 'onUnusedImagesDetected' },
    getLinkCardInfo: { action: 'getLinkCardInfo' },
  },
  decorators: [
    (Story) => (
      <div className="p-4 w-full h-screen">
        <Story />
      </div>
    ),
  ],
};

export default meta;
type Story = StoryObj<typeof PreviewMarkdownEditor>;

// モックの画像アップロード関数
const mockImageUpload = async (file: File): Promise<string> => {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(`https://example.com/images/${file.name}`);
    }, 1000);
  });
};

// モックのリンクカード情報取得関数
const mockGetLinkCardInfo = async (url: string) => {
  await new Promise((resolve) => setTimeout(resolve, 2000)); // 2秒の遅延
  console.log('リンクのOGPを取得します')
  return {
    url,
    imageUrl:
      'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png',
    title: 'Mock Link Card',
  };
};

export const Default: Story = {
  args: {
    initialValue:
      '# Welcome to PreviewMarkdownEditor\n\nThis is a **sample** text with a [link](https://example.com).\n\nTry editing this text to see the live preview!',
    onImageUpload: mockImageUpload,
    getLinkCardInfo: mockGetLinkCardInfo,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};

export const EmptyEditor: Story = {
  args: {
    onImageUpload: mockImageUpload,
    getLinkCardInfo: mockGetLinkCardInfo,
    onUnusedImagesDetected: (unusedImages: string[]) => {
      console.log('Unused images:', unusedImages);
    },
  },
};
