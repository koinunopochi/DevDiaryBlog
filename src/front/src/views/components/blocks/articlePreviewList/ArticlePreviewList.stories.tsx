import type { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import ArticlePreviewList from './ArticlePreviewList';

const meta: Meta<typeof ArticlePreviewList> = {
  title: 'blocks/ArticlePreviewList',
  component: ArticlePreviewList,
  parameters: {
    layout: 'padded',
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

type Story = StoryObj<typeof ArticlePreviewList>;

const defaultProfileImage =
  'http://127.0.0.1:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png';

const sampleArticles = [
  {
    id: 1,
    title: 'Reactの基礎',
    author: {
      displayName: '山田太郎',
      username: 'yamada_taro',
      profileImage: defaultProfileImage,
    },
    likes: 42,
    tags: [
      { id: '1', name: 'React' },
      { id: '2', name: 'プログラミング' },
    ],
  },
  {
    id: 2,
    title: 'TypeScriptで型安全なコーディング',
    author: {
      displayName: '佐藤花子',
      username: 'sato_hanako',
      profileImage: defaultProfileImage,
    },
    likes: 38,
    tags: [
      { id: '3', name: 'TypeScript' },
      { id: '4', name: '型安全' },
    ],
  },
  {
    id: 3,
    title: 'Tailwind CSSでレスポンシブデザイン',
    author: {
      displayName: '田中次郎',
      username: 'tanaka_jiro',
      profileImage: defaultProfileImage,
    },
    likes: 55,
    tags: [
      { id: '5', name: 'Tailwind CSS' },
      { id: '6', name: 'レスポンシブ' },
    ],
  },
];

export const DefaultArticlePreviewList: Story = {
  args: {
    articles: sampleArticles,
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};

export const SingleArticlePreviewList: Story = {
  args: {
    articles: [sampleArticles[0]],
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};

export const NoArticlesList: Story = {
  args: {
    articles: [],
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
  },
};
