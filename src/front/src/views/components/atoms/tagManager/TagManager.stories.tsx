import type { Meta, StoryObj } from '@storybook/react';
import TagManager from './TagManager';

const meta: Meta<typeof TagManager> = {
  title: 'atoms/TagManager',
  component: TagManager,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default meta;
type Story = StoryObj<typeof TagManager>;

const sampleTags = [
  'JavaScript',
  'React',
  'Vue',
  'Angular',
  'TypeScript',
  'Node.js',
  'Python',
  'Java',
  'C#',
  'PHP',
];

export const Default: Story = {
  args: {
    availableTags: sampleTags,
    initialSelectedTags: ['default'],
  },
};

export const EmptyList: Story = {
  args: {
    availableTags: [],
    initialSelectedTags: [],
  },
};

export const LongList: Story = {
  args: {
    availableTags: [
      ...sampleTags,
      'Ruby',
      'Go',
      'Rust',
      'Swift',
      'Kotlin',
      'Scala',
      'Haskell',
      'Erlang',
      'Clojure',
      'F#',
      'OCaml',
      'Dart',
      'Elixir',
      'Julia',
      'R',
      'MATLAB',
    ],
    initialSelectedTags: [],
  },
};

export const WithJapaneseTags: Story = {
  args: {
    availableTags: [
      'JavaScript',
      'React',
      'Vue',
      'Angular',
      'TypeScript',
      'Node.js',
      'Python',
      '機械学習',
      'データ分析',
      'ウェブ開発',
      'モバイルアプリ',
      'クラウド',
      'セキュリティ',
      'ブロックチェーン',
      'IoT',
      'AI',
    ],
    initialSelectedTags: [],
  },
};

export const PreSelectedTags: Story = {
  args: {
    availableTags: sampleTags,
    initialSelectedTags: ['React', 'TypeScript'],
  },
};
