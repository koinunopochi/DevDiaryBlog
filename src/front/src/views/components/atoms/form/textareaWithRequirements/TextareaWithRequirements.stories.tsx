import type { Meta, StoryObj } from '@storybook/react';
import TextareaWithRequirements from './TextareaWithRequirements';
import { useState } from 'react';

const meta = {
  title: 'atoms/form/TextareaWithRequirements',
  component: TextareaWithRequirements,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    placeholder: { control: 'text' },
    value: { control: 'text' },
    disabled: { control: 'boolean' },
    required: { control: 'boolean' },
    debounceTime: { control: 'number' },
  },
} satisfies Meta<typeof TextareaWithRequirements>;

export default meta;
type Story = StoryObj<typeof meta>;

const Template: Story = {
  render: (args) => {
    const [value, setValue] = useState(args.value || '');
    return (
      <TextareaWithRequirements
        {...args}
        value={value}
        onChange={(newValue, isValid) => {
          setValue(newValue);
          console.log('Is valid:', isValid);
        }}
      />
    );
  },
};

export const Default: Story = {
  ...Template,
  args: {
    label: '説明',
    placeholder: '説明を入力してください',
    requirements: [
      {
        key: 'minLength',
        label: '最低10文字以上',
        validator: (value: string) => value.length >= 10,
      },
    ],
    validate: (value: string) =>
      value.length >= 10 ? null : '最低10文字以上入力してください',
    debounceTime: 300,
  },
};

export const WithMultipleRequirements: Story = {
  ...Template,
  args: {
    label: 'プロフィール',
    placeholder: 'あなたのプロフィールを入力してください',
    requirements: [
      {
        key: 'minLength',
        label: '最低50文字以上',
        validator: (value: string) => value.length >= 50,
      },
      {
        key: 'maxLength',
        label: '最大200文字以下',
        validator: (value: string) => value.length <= 200,
      },
      {
        key: 'containsKeyword',
        label: '「趣味」という単語を含む',
        validator: (value: string) => value.includes('趣味'),
      },
    ],
    validate: (value: string) => {
      if (value.length < 50) return '最低50文字以上入力してください';
      if (value.length > 200) return '200文字以内で入力してください';
      if (!value.includes('趣味')) return '「趣味」という単語を含めてください';
      return null;
    },
    debounceTime: 500,
  },
};

export const WithInitialValue: Story = {
  ...Template,
  args: {
    ...Default.args,
    value: 'これは初期値です。十分な長さがあります。',
  },
};

export const Disabled: Story = {
  ...Template,
  args: {
    ...Default.args,
    disabled: true,
    value: 'この入力欄は無効化されています。',
  },
};

export const Required: Story = {
  ...Template,
  args: {
    ...Default.args,
    required: true,
  },
};

export const WithCustomStyle: Story = {
  ...Template,
  args: {
    ...Default.args,
    className: 'bg-gray-100 border-2 border-blue-500 rounded-lg p-2',
  },
};

export const WithLongContent: Story = {
  ...Template,
  args: {
    label: '長文入力',
    placeholder: '長い文章を入力してください',
    requirements: [
      {
        key: 'minLength',
        label: '最低200文字以上',
        validator: (value: string) => value.length >= 200,
      },
    ],
    validate: (value: string) =>
      value.length >= 200 ? null : '最低200文字以上入力してください',
    value: ''.padStart(180, 'これは長文入力のテストです。'),
    debounceTime: 1000,
  },
};

export const WithFastDebounce: Story = {
  ...Template,
  args: {
    ...Default.args,
    label: '高速デバウンス',
    placeholder: '素早く入力してみてください',
    debounceTime: 100,
  },
};
