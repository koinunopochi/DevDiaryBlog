import type { Meta, StoryObj } from '@storybook/react';
import Input from './Input';

const meta = {
  title: 'atoms/form/Input',
  component: Input,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    placeholder: { control: 'text' },
    initialValue: { control: 'text' },
    disabled: { control: 'boolean' },
    type: {
      control: {
        type: 'select',
        options: ['text', 'password', 'email', 'number'],
      },
    },
  },
} satisfies Meta<typeof Input>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    placeholder: '入力してください',
  },
};

export const WithLabel: Story = {
  args: {
    label: 'ユーザー名',
    placeholder: 'ユーザー名を入力',
  },
};

export const WithInitialValue: Story = {
  args: {
    label: 'メールアドレス',
    placeholder: 'example@example.com',
    initialValue: 'user@example.com',
    validate: (value: string) => {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(value)
        ? null
        : '有効なメールアドレスを入力してください';
    },
  },
};

export const WithMaxLengthValidation: Story = {
  args: {
    label: 'コメント',
    placeholder: 'コメントを入力（最大100文字）',
    validate: (value: string) => {
      return value.length <= 100 ? null : '最大100文字までです';
    },
  },
};

export const WithCustomValidation: Story = {
  args: {
    label: '数値入力',
    placeholder: '0以上100以下の数値を入力',
    type: 'number',
    onInputChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
    validate: (value: string) => {
      const num = Number(value);
      return num >= 0 && num <= 100
        ? null
        : '0以上100以下の数値を入力してください';
    },
  },
};
