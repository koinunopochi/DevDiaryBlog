import { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import InputWithRequirements from './InputWithRequirements';

const meta: Meta<typeof InputWithRequirements> = {
  title: 'atoms/form/InputWithRequirements',
  component: InputWithRequirements,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    value: { control: 'text' },
    onChange: { action: 'onChange' },
    type: { control: 'select', options: ['text', 'email', 'password'] },
    placeholder: { control: 'text' },
    toggleVisibility: { control: 'boolean' },
  },
};

export default meta;
type Story = StoryObj<typeof meta>;

const defaultRequirements = [
  {
    key: 'notEmpty',
    label: '空でないこと',
    validator: (value: string) => value.trim().length > 0,
  },
  {
    key: 'minLength',
    label: '最低5文字以上',
    validator: (value: string) => value.length >= 5,
  },
];

const validateInput = (value: string): string | null => {
  return defaultRequirements.every((req) => req.validator(value))
    ? null
    : '全ての要件を満たす必要があります';
};

const InputWithRequirementsWrapper = (args: any) => {
  const [value, setValue] = useState(args.value || '');
  const handleChange = (newValue: string, isValid: boolean) => {
    setValue(newValue);
    args.onChange(newValue, isValid);
  };
  return (
    <InputWithRequirements {...args} value={value} onChange={handleChange} />
  );
};

export const Default: Story = {
  render: (args) => <InputWithRequirementsWrapper {...args} />,
  args: {
    label: 'サンプル入力',
    value: '',
    requirements: defaultRequirements,
    type: 'text',
    placeholder: 'テキストを入力',
    validate: validateInput,
  },
};

export const Email: Story = {
  render: (args) => <InputWithRequirementsWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: '',
    requirements: [
      {
        key: 'validEmail',
        label: '有効なメールアドレス形式',
        validator: (value: string) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
      },
    ],
    type: 'email',
    placeholder: 'example@example.com',
    validate: (value: string) =>
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
        ? null
        : '有効なメールアドレスを入力してください',
  },
};

export const Password: Story = {
  render: (args) => <InputWithRequirementsWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: '',
    requirements: [
      {
        key: 'length',
        label: '8文字以上',
        validator: (value: string) => value.length >= 8,
      },
      {
        key: 'uppercase',
        label: '大文字を含む',
        validator: (value: string) => /[A-Z]/.test(value),
      },
      {
        key: 'lowercase',
        label: '小文字を含む',
        validator: (value: string) => /[a-z]/.test(value),
      },
      {
        key: 'number',
        label: '数字を含む',
        validator: (value: string) => /[0-9]/.test(value),
      },
    ],
    type: 'password',
    placeholder: 'パスワードを入力',
    validate: (value: string) => {
      const isValid =
        value.length >= 8 &&
        /[A-Z]/.test(value) &&
        /[a-z]/.test(value) &&
        /[0-9]/.test(value);
      return isValid ? null : '全ての要件を満たすパスワードを入力してください';
    },
    toggleVisibility: true,
  },
};

export const WithInitialValue: Story = {
  render: (args) => <InputWithRequirementsWrapper {...args} />,
  args: {
    ...Default.args,
    value: 'Initial Value',
  },
};

export const WithLongLabel: Story = {
  render: (args) => <InputWithRequirementsWrapper {...args} />,
  args: {
    ...Default.args,
    label:
      'これは非常に長いラベルで、レイアウトがどのように処理されるかをテストします',
  },
};

export const WithManyRequirements: Story = {
  render: (args) => <InputWithRequirementsWrapper {...args} />,
  args: {
    ...Default.args,
    requirements: [
      ...defaultRequirements,
      {
        key: 'hasSpecialChar',
        label: '特殊文字を含む',
        validator: (value: string) => /[!@#$%^&*(),.?":{}|<>]/.test(value),
      },
      {
        key: 'hasNumber',
        label: '数字を含む',
        validator: (value: string) => /[0-9]/.test(value),
      },
      {
        key: 'noConsecutiveChars',
        label: '連続する文字を含まない',
        validator: (value: string) => !/(.)\1/.test(value),
      },
    ],
  },
};
