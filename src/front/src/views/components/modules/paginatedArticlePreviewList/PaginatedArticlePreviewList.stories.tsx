/* eslint-disable @typescript-eslint/no-explicit-any */
import type { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import { useState } from 'react';
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
];

// 模擬的なAPIコール関数
const fetchArticles = (
  page: number,
  itemsPerPage: number,
  totalItems: number
) => {
  const startIndex = (page - 1) * itemsPerPage;
  const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
  return Array.from({ length: endIndex - startIndex }, (_, index) => ({
    ...sampleArticles[index % sampleArticles.length],
    id: startIndex + index + 1,
    title: `記事 ${startIndex + index + 1}: ${sampleArticles[index % sampleArticles.length].title}`,
  }));
};

const PaginatedArticlePreviewListWithState = (args: any) => {
  const [currentPage, setCurrentPage] = useState(1);
  const articles = fetchArticles(
    currentPage,
    args.itemsPerPage,
    args.totalItems
  );

  return (
    <PaginatedArticlePreviewList
      {...args}
      articles={articles}
      currentPage={currentPage}
      onPageChange={setCurrentPage}
    />
  );
};

export const DefaultPaginatedArticlePreviewList: Story = {
  render: (args) => <PaginatedArticlePreviewListWithState {...args} />,
  args: {
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 5,
    totalItems: 23,
  },
};

export const SinglePageList: Story = {
  render: (args) => <PaginatedArticlePreviewListWithState {...args} />,
  args: {
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 5,
    totalItems: 3,
  },
};

export const NoArticlesList: Story = {
  render: (args) => <PaginatedArticlePreviewListWithState {...args} />,
  args: {
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 5,
    totalItems: 0,
  },
};

export const LargeItemsPerPage: Story = {
  render: (args) => <PaginatedArticlePreviewListWithState {...args} />,
  args: {
    onTagClick: (name, id) => console.log(`Tag clicked: ${name} (ID: ${id})`),
    itemsPerPage: 50,
    totalItems: 2000,
  },
};
