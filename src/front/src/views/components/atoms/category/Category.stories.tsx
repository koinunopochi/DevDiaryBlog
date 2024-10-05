import type { Meta, StoryObj } from '@storybook/react';
import Category from './Category';

const meta: Meta<typeof Category> = {
  title: 'atoms/Category',
  component: Category,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    category: { control: 'object' },
  },
};

export default meta;
type Story = StoryObj<typeof Category>;

export const Default: Story = {
  args: {
    category: {
      id: '1',
      name: 'テクノロジー',
      description: '最新のテクノロジートレンドと革新に関する記事',
      tags: [
        { id: 'tag-1', name: 'AI' },
        { id: 'tag-2', name: 'ブロックチェーン' },
        { id: 'tag-3', name: 'IoT' },
      ],
    },
  },
};

export const LongDescription: Story = {
  args: {
    category: {
      id: '2',
      name: '科学',
      description:
        '科学の世界の最新の発見と革新的な研究結果について詳しく解説します。物理学、化学、生物学、天文学など、幅広い分野をカバーしています。',
      tags: [
        { id: 'tag-4', name: '物理' },
        { id: 'tag-5', name: '化学' },
        { id: 'tag-6', name: '生物学' },
        { id: 'tag-7', name: '天文学' },
      ],
    },
  },
};

export const ManyTags: Story = {
  args: {
    category: {
      id: '3',
      name: 'プログラミング',
      description: 'プログラミング言語、フレームワーク、ツールに関する情報',
      tags: [
        { id: 'tag-8', name: 'JavaScript' },
        { id: 'tag-9', name: 'Python' },
        { id: 'tag-10', name: 'React' },
        { id: 'tag-11', name: 'Node.js' },
        { id: 'tag-12', name: 'Docker' },
        { id: 'tag-13', name: 'Git' },
        { id: 'tag-14', name: 'TypeScript' },
        { id: 'tag-15', name: 'Vue.js' },
      ],
    },
  },
};

export const NoTags: Story = {
  args: {
    category: {
      id: '4',
      name: '一般ニュース',
      description: '世界中の最新ニュースと時事問題',
      tags: [],
    },
  },
};

export const JapaneseContent: Story = {
  args: {
    category: {
      id: '5',
      name: '日本文化',
      description: '日本の伝統、現代文化、芸術に関する記事',
      tags: [
        { id: 'tag-16', name: '伝統' },
        { id: 'tag-17', name: '現代アート' },
        { id: 'tag-18', name: '食文化' },
        { id: 'tag-19', name: 'アニメ' },
      ],
    },
  },
};

export const LongCategoryName: Story = {
  args: {
    category: {
      id: '6',
      name: 'エンターテインメントと映画産業の最新トレンド',
      description:
        '映画、テレビ、音楽、ゲームなどのエンターテインメント業界の最新情報',
      tags: [
        { id: 'tag-20', name: '映画' },
        { id: 'tag-21', name: 'テレビ' },
        { id: 'tag-22', name: '音楽' },
        { id: 'tag-23', name: 'ゲーム' },
      ],
    },
  },
};
