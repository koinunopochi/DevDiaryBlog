import React, { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import InputPassword from './InputPassword';

const meta: Meta<typeof InputPassword> = {
  title: 'atoms/form/InputPassword',
  component: InputPassword,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    value: { control: 'text' },
    onChange: { action: 'changed' },
  },
};

export default meta;
type Story = StoryObj<typeof meta>;

const InputPasswordWrapper = (
  args: React.ComponentProps<typeof InputPassword>
) => {
  const [value, setValue] = useState(args.value || '');
  return (
    <InputPassword
      {...args}
      value={value}
      onChange={(newValue, isValid) => {
        setValue(newValue);
        args.onChange(newValue, isValid);
      }}
    />
  );
};

// デフォルトの onChange ハンドラー
const defaultOnChange = (value: string, isValid: boolean) => {
  console.log(`Value: ${value}, Is Valid: ${isValid}`);
};

export const Default: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: '',
    onChange: defaultOnChange,
  },
};

export const WithValidPassword: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'ValidP@ssw0rd123',
    onChange: defaultOnChange,
  },
};

export const WithInvalidPassword: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'weak',
    onChange: defaultOnChange,
  },
};

export const WithoutLowercase: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'NOLOWERCASE123@',
    onChange: defaultOnChange,
  },
};

export const WithoutUppercase: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'nouppercase123@',
    onChange: defaultOnChange,
  },
};

export const WithoutNumber: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'NoNumberHere@',
    onChange: defaultOnChange,
  },
};

export const WithoutSymbol: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'NoSymbolHere123',
    onChange: defaultOnChange,
  },
};

export const TooShort: Story = {
  render: (args) => <InputPasswordWrapper {...args} />,
  args: {
    label: 'パスワード',
    value: 'Short1@',
    onChange: defaultOnChange,
  },
};
