import type { Meta, StoryObj } from '@storybook/react';
import Profile from './Profile';

const meta = {
  title: 'Blocks/Profile',
  component: Profile,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    userData: { control: 'object' },
    isEditable: { control: 'boolean' },
    onEditProfile: { action: 'clicked' },
  },
} satisfies Meta<typeof Profile>;

export default meta;
type Story = StoryObj<typeof meta>;

const baseUserData = {
  id: 'user0000-5ef5-48e3-933c-a70bd9de5f86',
  user: {
    name: '7zdb944rye',
    email: 'hogehoge@hh.com',
    status: 'Active',
    createdAt: '2024-09-04T01:27:31+09:00',
    updatedAt: '2024-09-04T01:27:31+09:00',
  },
  profile: {
    displayName: 'userName',
    bio: '初心者の文系学生 薄く広く学習中',
    avatarUrl:
      'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png',
    socialLinks: {
      github: 'https://github.com',
      twitter: 'https://twitter.com',
      zenn: 'https://zenn.dev',
      qiita: 'https://qiita.com',
    },
  },
};

export const DefaultProfile: Story = {
  args: {
    userData: baseUserData,
    onEditProfile: () => {
      alert('プロフィールを編集する');
    },
    isEditable: true,
  },
};

export const NoSocialLinks: Story = {
  args: {
    userData: {
      ...baseUserData,
      profile: {
        ...baseUserData.profile,
        socialLinks: {},
      },
    },
    onEditProfile: () => {
      alert('プロフィールを編集する');
    },
    isEditable: true,
  },
};

export const LongBio: Story = {
  args: {
    userData: {
      ...baseUserData,
      profile: {
        ...baseUserData.profile,
        bio: 'これは非常に長いバイオです。プロフィールコンポーネントが長いテキストをどのように扱うかをテストします。テキストが長すぎる場合、適切に折り返されるか、省略されるかを確認します。',
      },
    },
    onEditProfile: () => {
      alert('プロフィールを編集する');
    },
    isEditable: false,
  },
};

export const NoProfile: Story = {
  args: {
    userData: {
      ...baseUserData,
      profile: null,
    },
    onEditProfile: () => {
      alert('プロフィールを編集する');
    },
    isEditable: false,
  },
};

export const TooShortProfile: Story = {
  args: {
    userData: {
      ...baseUserData,
      profile: {
        ...baseUserData.profile,
        displayName: 'a',
        bio: 'a',
      },
    },
    onEditProfile: () => {
      alert('プロフィールを編集する');
    },
    isEditable: false,
  },
};

export const ToLongUrl: Story = {
  args: {
    userData: {
      ...baseUserData,
      profile: {
        ...baseUserData.profile,
        socialLinks: {
          github: 'https://github.com/7zdb944rye',
          twitter: 'https://twitter.com/7zdb944rye',
          zenn: 'https://zenn.dev/7zdb944rye',
          qiita: 'https://qiita.com/7zdb944ryedfghjkjhgfdfghjkllkjdfghjkl',
          'とても長いサイト名である。折り返されるか確かめたい。とてもとても長いサイトです。':
            'https://qiita.com/7zdb944ryedfghjkjhgfdfghjkllkjdfghjkl',
        },
      },
    },
    onEditProfile: () => {
      alert('プロフィールを編集する');
    },
    isEditable: false,
  },
};
