import type { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import PaginatedArticlePreviewList from './PaginatedArticlePreviewList';

const meta: Meta<typeof PaginatedArticlePreviewList> = {
  title: 'modules/PaginatedArticlePreviewList',
  component: PaginatedArticlePreviewList,
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

type Story = StoryObj<typeof PaginatedArticlePreviewList>;

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
  // 追加の記事をここに追加して、ページネーションをテストできるようにします
  // ...
];

// 20個の記事を生成
const generateArticles = (count: number) => {
  return Array.from({ length: count }, (_, index) => ({
    ...sampleArticles[index % sampleArticles.length],
    id: index + 1,
    title: `記事 ${index + 1}: ${sampleArticles[index % sampleArticles.length].title}`,
  }));
};

export const DefaultPaginatedArticlePreviewList: Story = {
  args: {
    articles: generateArticles(20),
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 5,
  },
};

export const SinglePageList: Story = {
  args: {
    articles: sampleArticles,
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 5,
  },
};

export const NoArticlesList: Story = {
  args: {
    articles: [],
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 5,
  },
};

export const LargeItemsPerPage: Story = {
  args: {
    articles: generateArticles(50),
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 10,
  },
};
