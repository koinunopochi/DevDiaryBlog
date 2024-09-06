import type { Meta, StoryObj } from '@storybook/react';
import Textarea from './Textarea';

const meta = {
  title: 'atoms/form/Textarea',
  component: Textarea,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    placeholder: { control: 'text' },
    initialValue: { control: 'text' },
    disabled: { control: 'boolean' },
    rows: { control: 'number' },
  },
} satisfies Meta<typeof Textarea>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    placeholder: 'テキストを入力してください',
    rows: 4,
  },
};

export const WithLabel: Story = {
  args: {
    label: 'コメント',
    placeholder: 'コメントを入力してください',
    rows: 4,
  },
};

export const WithInitialValue: Story = {
  args: {
    label: '自己紹介',
    placeholder: '自己紹介を入力してください',
    initialValue: 'こんにちは、私は...',
    rows: 5,
  },
};

export const WithMaxLengthValidation: Story = {
  args: {
    label: '短いメッセージ',
    placeholder: '最大100文字で入力してください',
    rows: 3,
    validate: (value: string) => {
      return value.length <= 100 ? null : '最大100文字までです';
    },
  },
};

export const WithCustomValidation: Story = {
  args: {
    label: 'キーワード入力',
    placeholder: 'カンマ区切りで3つ以上のキーワードを入力してください',
    rows: 2,
    onTextareaChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
    validate: (value: string) => {
      const keywords = value.split(',').filter((k) => k.trim() !== '');
      return keywords.length >= 3
        ? null
        : '3つ以上のキーワードを入力してください';
    },
  },
};

export const CustomStyle: Story = {
  args: {
    label: 'カスタムスタイル',
    placeholder: 'カスタムスタイル',
    rows: 3,
    className:"border-2 border-pink-400 rounded-md p-2"
  },
};
