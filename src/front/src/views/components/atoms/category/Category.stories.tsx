import type { Meta, StoryObj } from '@storybook/react';
import Category from './Category';
import { action } from '@storybook/addon-actions';

const meta: Meta<typeof Category> = {
  title: 'atoms/Category',
  component: Category,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    category: { control: 'object' },
    isSelected: { control: 'boolean' },
    onTagClick: { action: 'onTagClick' },
    onCategoryClick: { action: 'onCategoryClick' },
  },
};

export default meta;
type Story = StoryObj<typeof Category>;

const sampleCategory = {
  id: '1',
  name: 'テクノロジー',
  description: '最新のテクノロジートレンドと革新に関する記事',
  tags: [
    { id: 'tag-1', name: 'AI' },
    { id: 'tag-2', name: 'ブロックチェーン' },
    { id: 'tag-3', name: 'IoT' },
  ],
};

export const Default: Story = {
  args: {
    category: sampleCategory,
    onTagClick: action('onTagClick'),
    onCategoryClick: action('onCategoryClick'),
  },
};

export const Selected: Story = {
  args: {
    ...Default.args,
    isSelected: true,
  },
};

export const LongDescription: Story = {
  args: {
    ...Default.args,
    category: {
      ...sampleCategory,
      description:
        'これは非常に長い説明文です。長い説明文がある場合のレイアウトを確認するために使用します。テクノロジーの進歩は日々加速しており、私たちの生活に大きな影響を与えています。',
    },
  },
};

export const ManyTags: Story = {
  args: {
    ...Default.args,
    category: {
      ...sampleCategory,
      tags: [
        { id: 'tag-1', name: 'AI' },
        { id: 'tag-2', name: 'ブロックチェーン' },
        { id: 'tag-3', name: 'IoT' },
        { id: 'tag-4', name: '5G' },
        { id: 'tag-5', name: 'クラウド' },
        { id: 'tag-6', name: 'サイバーセキュリティ' },
      ],
    },
  },
};
