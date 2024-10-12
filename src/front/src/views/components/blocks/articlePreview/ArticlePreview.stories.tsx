import type { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import ArticlePreview from './ArticlePreview';

const meta: Meta<typeof ArticlePreview> = {
  title: 'blocks/ArticlePreview',
  component: ArticlePreview,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  decorators: [
    (Story) => (
      <MemoryRouter>
        <Story />
      </MemoryRouter>
    ),
  ],
};

export default meta;

type Story = StoryObj<typeof ArticlePreview>;

const sampleTags = [
  { id: '1', name: 'React' },
  { id: '2', name: 'TypeScript' },
  { id: '3', name: 'Tailwind CSS' },
];

const defaultProfileImage =
  'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png';

export const DefaultArticlePreview: Story = {
  args: {
    id: 1,
    title: 'Reactの基礎',
    author: {
      displayName: '山田太郎',
      username: 'yamada_taro',
      profileImage: defaultProfileImage,
    },
    likes: 42,
    tags: sampleTags,
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};

export const LongTitleArticlePreview: Story = {
  args: {
    id: 2,
    title:
      'これは非常に長い記事のタイトルです。タイトルが長すぎる場合の表示を確認するためのものです。',
    author: {
      displayName: '佐藤花子',
      username: 'sato_hanako',
      profileImage: defaultProfileImage,
    },
    likes: 100,
    tags: sampleTags.slice(0, 2),
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};

export const NoLikesArticlePreview: Story = {
  args: {
    id: 3,
    title: '新着記事',
    author: {
      displayName: '田中次郎',
      username: 'tanaka_jiro',
      profileImage: defaultProfileImage,
    },
    likes: 0,
    tags: [sampleTags[2]],
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};

export const HighLikesArticlePreview: Story = {
  args: {
    id: 4,
    title: '人気記事',
    author: {
      displayName: '鈴木一郎',
      username: 'suzuki_ichiro',
      profileImage: defaultProfileImage,
    },
    likes: 9999,
    tags: sampleTags,
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};
