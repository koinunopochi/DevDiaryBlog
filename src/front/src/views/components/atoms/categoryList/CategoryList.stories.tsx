import type { Meta, StoryObj } from '@storybook/react';
import CategoryList, { CategoryData } from './CategoryList';
import { action } from '@storybook/addon-actions';

const meta: Meta<typeof CategoryList> = {
  title: 'Components/CategoryList',
  component: CategoryList,
  parameters: {
    layout: 'padded',
  },
  tags: ['autodocs'],
  argTypes: {
    categories: { control: 'object' },
    onCategoryClick: { action: 'category clicked' },
    onTagClick: { action: 'tag clicked' },
    selectedCategoryId: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof CategoryList>;

const sampleCategories: CategoryData[] = [
  {
    id: '1',
    name: 'テクノロジー',
    description: '最新のテクノロジートレンドと革新に関する記事',
    tags: [
      { id: 'tag-1', name: 'AI' },
      { id: 'tag-2', name: 'ブロックチェーン' },
      { id: 'tag-3', name: 'IoT' },
    ],
  },
  {
    id: '2',
    name: '科学',
    description: '科学の世界の最新の発見と革新的な研究結果について解説',
    tags: [
      { id: 'tag-4', name: '物理' },
      { id: 'tag-5', name: '化学' },
      { id: 'tag-6', name: '生物学' },
    ],
  },
  {
    id: '3',
    name: 'プログラミング',
    description: 'プログラミング言語、フレームワーク、ツールに関する情報',
    tags: [
      { id: 'tag-7', name: 'JavaScript' },
      { id: 'tag-8', name: 'Python' },
      { id: 'tag-9', name: 'React' },
    ],
  },
];

export const Default: Story = {
  args: {
    categories: sampleCategories,
    onCategoryClick: action('category clicked'),
    onTagClick: action('tag clicked'),
  },
};

export const WithSelection: Story = {
  args: {
    ...Default.args,
    selectedCategoryId: '2',
  },
};

export const SingleCategory: Story = {
  args: {
    ...Default.args,
    categories: [sampleCategories[0]],
  },
};

export const ManyCategories: Story = {
  args: {
    ...Default.args,
    categories: [
      ...sampleCategories,
      ...sampleCategories.map((cat) => ({ ...cat, id: `${cat.id}-copy` })),
    ],
  },
};
