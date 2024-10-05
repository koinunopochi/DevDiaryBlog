import type { Meta, StoryObj } from '@storybook/react';
import SaveSettingsModal from './SaveSettingsModal';
import { action } from '@storybook/addon-actions';

const meta: Meta<typeof SaveSettingsModal> = {
  title: 'blocks/SaveSettingsModal',
  component: SaveSettingsModal,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default meta;
type Story = StoryObj<typeof SaveSettingsModal>;

const sampleCategories = [
  {
    id: '1',
    name: 'フロントエンド',
    description: 'フロントエンド開発に関するカテゴリ',
    tags: [
      { id: '1', name: 'React' },
      { id: '2', name: 'Vue' },
    ],
  },
  {
    id: '2',
    name: 'バックエンド',
    description: 'バックエンド開発に関するカテゴリ',
    tags: [
      { id: '3', name: 'Node.js' },
      { id: '4', name: 'Python' },
    ],
  },
  {
    id: '3',
    name: 'デザイン',
    description: 'UIデザインに関するカテゴリ',
    tags: [
      { id: '5', name: 'Figma' },
      { id: '6', name: 'Sketch' },
    ],
  },
];

const sampleTags = [
  'JavaScript',
  'React',
  'Vue',
  'Node.js',
  'Python',
  'Figma',
  'Sketch',
  'TypeScript',
  'GraphQL',
  'Docker',
];

export const Default: Story = {
  args: {
    categories: sampleCategories,
    availableTags: sampleTags,
    onSave: action('onSave'),
    onCategoryClick: action('onCategoryClick'),
    onTagClick: action('onTagClick'),
  },
};

export const WithSelectedCategory: Story = {
  args: {
    ...Default.args,
    selectedCategoryId: '1',
  },
};

export const WithNoCategories: Story = {
  args: {
    ...Default.args,
    categories: [],
  },
};

export const WithManyCategories: Story = {
  args: {
    ...Default.args,
    categories: [
      ...sampleCategories,
      {
        id: '4',
        name: 'モバイル開発',
        description: 'モバイルアプリ開発に関するカテゴリ',
        tags: [
          { id: '7', name: 'React Native' },
          { id: '8', name: 'Swift' },
        ],
      },
      {
        id: '5',
        name: 'データベース',
        description: 'データベース技術に関するカテゴリ',
        tags: [
          { id: '9', name: 'MySQL' },
          { id: '10', name: 'MongoDB' },
        ],
      },
      {
        id: '6',
        name: 'DevOps',
        description: 'DevOps関連のカテゴリ',
        tags: [
          { id: '11', name: 'Docker' },
          { id: '12', name: 'Kubernetes' },
        ],
      },
    ],
  },
};

export const WithManyTags: Story = {
  args: {
    ...Default.args,
    availableTags: [
      ...sampleTags,
      'Angular',
      'Vue.js',
      'Express',
      'Django',
      'Ruby on Rails',
      'Adobe XD',
      'InVision',
      'AWS',
      'Azure',
      'Google Cloud',
      'CI/CD',
      'Jest',
      'Cypress',
    ],
  },
};
