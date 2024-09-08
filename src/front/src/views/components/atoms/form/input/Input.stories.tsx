import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import Input from './Input';

const meta: Meta<typeof Input> = {
  title: 'atoms/form/Input',
  component: Input,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    placeholder: { control: 'text' },
    value: { control: 'text' },
    disabled: { control: 'boolean' },
    type: {
      control: {
        type: 'select',
        options: ['text', 'password', 'email', 'number'],
      },
    },
    onChange: { action: 'changed' },
  },
};

export default meta;
type Story = StoryObj<typeof Input>;

export const Default: Story = {
  args: {
    placeholder: '入力してください',
    value: '',
    onChange: (value, isValid) =>
      console.log('Input is updated', value, isValid),
  },
};

export const WithLabel: Story = {
  args: {
    label: 'ユーザー名',
    placeholder: 'ユーザー名を入力',
    value: '',
  },
};

export const WithValue: Story = {
  args: {
    label: 'メールアドレス',
    placeholder: 'example@example.com',
    value: 'user@example.com',
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
    value: '',
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
    value: '',
    validate: (value: string) => {
      const num = Number(value);
      return num >= 0 && num <= 100
        ? null
        : '0以上100以下の数値を入力してください';
    },
  },
};

export const CustomStyle: Story = {
  args: {
    label: 'text',
    placeholder: 'なんでもよい',
    type: 'text',
    value: '',
    className:
      'rounded-md border-2 border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-blue-100',
  },
};

export const ParentControlled: Story = {
  render: function Render(args) {
    const [value, setValue] = React.useState(args.value);
    const [isValid, setIsValid] = React.useState(true);

    const handleChange = (newValue: string, newIsValid: boolean) => {
      setValue(newValue);
      setIsValid(newIsValid);
      args.onChange(newValue, newIsValid);
      console.log('input is updated', newValue);
    };

    const validate = (value: string) => {
      return value.length < 3 ? '3文字以上入力してください' : null;
    };

    return (
      <div>
        <Input
          {...args}
          value={value}
          onChange={handleChange}
          validate={validate}
        />
        <button onClick={() => setValue('親から設定')}>親から値を設定</button>
        <p>現在の値: {value}</p>
        <p>バリデーション: {isValid ? '有効' : '無効'}</p>
      </div>
    );
  },
  args: {
    label: '親制御の入力',
    placeholder: '入力してください',
    value: '',
  },
};
