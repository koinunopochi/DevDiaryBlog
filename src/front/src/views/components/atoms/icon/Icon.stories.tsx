import type { Meta, StoryObj } from '@storybook/react';
import Icon from './Icon';
import unKnownUser from '@img/unknown-user.png'

const meta = {
  title: 'atoms/Icon',
  component: Icon,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    size: {
      control: {
        type: 'select',
        options: ['w-6 h-6', 'w-8 h-8', 'w-10 h-10', 'w-20 h-20'],
      },
    },
    shape: {
      control: {
        type: 'select',
        options: ['rounded-full', 'rounded-lg', ''],
      },
    },
    isButton: { control: 'boolean' },
    className: { control: 'text' },
  },
} satisfies Meta<typeof Icon>;

export default meta;
type Story = StoryObj<typeof meta>;

export const ProfileIcon: Story = {
  args: {
    src: 'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png',
    alt: 'プロフィールアイコン',
    size: 'w-10 h-10',
    shape: 'rounded-full',
    className: '',
  },
};

export const HeaderIcon: Story = {
  args: {
    src: 'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_106670_128.png',
    alt: 'ヘッダーアイコン',
    size: 'w-8 h-8',
    shape: 'rounded-lg',
    className: '',
  },
};

export const SendIcon: Story = {
  args: {
    src: 'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_106830_128.png',
    alt: '送信',
    isButton: true,
    onClick: () => {
      console.log('send');
    },
    className: '',
  },
};

export const CustomStyledIcon: Story = {
  args: {
    src: 'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_107620_128.png',
    alt: 'カスタムスタイルアイコン',
    size: 'w-20 h-20',
    shape: 'rounded-lg',
    isButton: true,
    className:
      'border-4 border-blue-500 hover:border-red-500 transition-colors duration-300',
  },
};

export const DefaultIconError: Story = {
  args: {
    src: 'http://127.0.0.1:9000/not-exist/path',
    alt: 'カスタムスタイルアイコン',
    size: 'w-20 h-20',
    shape: 'rounded-lg',
  },
};

export const CustomIconError: Story = {
  args: {
    src: 'http://127.0.0.1:9000/not-exist/path',
    alt: 'カスタムスタイルアイコン',
    size: 'w-20 h-20',
    shape: 'rounded-lg',
    defaultSrc: unKnownUser
  },
};
