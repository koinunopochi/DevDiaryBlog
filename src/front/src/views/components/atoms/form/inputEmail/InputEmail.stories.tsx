import React, { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import InputEmail from './InputEmail';

const meta: Meta<typeof InputEmail> = {
  title: 'atoms/form/InputEmail',
  component: InputEmail,
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

const InputEmailWrapper = (args: React.ComponentProps<typeof InputEmail>) => {
  const [value, setValue] = useState(args.value || '');
  return (
    <InputEmail
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
  render: (args) => <InputEmailWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: '',
    onChange: defaultOnChange,
  },
};

export const WithInitialValue: Story = {
  render: (args) => <InputEmailWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: 'john.doe@example.com',
    onChange: defaultOnChange,
  },
};

export const WithInvalidInitialValue: Story = {
  render: (args) => <InputEmailWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: 'invalid-email',
    onChange: defaultOnChange,
  },
};

export const WithEmptyValue: Story = {
  render: (args) => <InputEmailWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: '',
    onChange: defaultOnChange,
  },
};

export const WithLongEmail: Story = {
  render: (args) => <InputEmailWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: 'very.long.email.address.that.is.still.valid@example.com',
    onChange: defaultOnChange,
  },
};

export const WithSpecialCharacters: Story = {
  render: (args) => <InputEmailWrapper {...args} />,
  args: {
    label: 'メールアドレス',
    value: 'user+tag@example.co.uk',
    onChange: defaultOnChange,
  },
};
