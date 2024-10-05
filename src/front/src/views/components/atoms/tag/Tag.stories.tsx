import type { Meta, StoryObj } from '@storybook/react';
import Tag  from './Tag';

const meta: Meta<typeof Tag> = {
  title: 'atoms/Tag',
  component: Tag,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    id: { control: 'text' },
    name: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof Tag>;

export const Default: Story = {
  args: {
    id: 'tag-1',
    name: 'テクノロジー',
  },
};

export const ShortName: Story = {
  args: {
    id: 'tag-2',
    name: 'AI',
  },
};

export const LongName: Story = {
  args: {
    id: 'tag-3',
    name: 'ブロックチェーンと分散型台帳技術',
  },
};

export const JapaneseContent: Story = {
  args: {
    id: 'tag-4',
    name: '人工知能',
  },
};

export const SpecialCharacters: Story = {
  args: {
    id: 'tag-5',
    name: 'C++&Python',
  },
};

export const NumbersAndSymbols: Story = {
  args: {
    id: 'tag-6',
    name: 'Web3.0',
  },
};

export const WithOnClick: Story = {
  args: {
    id: 'tag-6',
    name: 'Web3.0',
    onClick:(id:string,name:string)=>alert(`${id},${name}`)
  },
};

// タグの複数表示例
export const MultipleTagsExample: Story = {
  render: () => (
    <div className="flex flex-wrap gap-2">
      <Tag id="tag-7" name="フロントエンド" />
      <Tag id="tag-8" name="バックエンド" />
      <Tag id="tag-9" name="DevOps" />
      <Tag id="tag-10" name="機械学習" />
    </div>
  ),
};
