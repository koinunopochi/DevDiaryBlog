import React, { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import InputUrl from './InputUrl';

const meta: Meta<typeof InputUrl> = {
  title: 'atoms/form/InputUrl',
  component: InputUrl,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    value: { control: 'text' },
    onChange: { action: 'changed' },
    placeholder: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof InputUrl>;

const InputUrlWrapper = (args: React.ComponentProps<typeof InputUrl>) => {
  const [value, setValue] = useState(args.value || '');
  return (
    <InputUrl
      {...args}
      value={value}
      onChange={(newValue, isValid) => {
        setValue(newValue);
        args.onChange(newValue, isValid);
      }}
    />
  );
};

export const Default: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: 'ウェブサイト URL',
    placeholder: 'https://example.com',
    value: '',
  },
};

export const WithInitialValue: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: 'ブログ URL',
    value: 'https://myblog.example.com',
    placeholder: 'https://yourblog.com',
  },
};

export const WithCustomOnChange: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: 'SNS プロフィール URL',
    placeholder: 'https://social.example.com/profile',
    value: '',
    onChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const InvalidUrl: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: 'ウェブサイト URL',
    value: 'not-a-valid-url',
    placeholder: 'https://example.com',
  },
};

export const MaxLengthEdgeCase: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: '長い URL',
    value: `https://example.com/${'a'.repeat(230)}`,
    placeholder: 'https://example.com',
  },
};

export const ExceedsMaxLength: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: '長すぎる URL',
    value: `https://example.com/${'a'.repeat(231)}`,
    placeholder: 'https://example.com',
  },
};

export const EmptyInput: Story = {
  render: (args) => <InputUrlWrapper {...args} />,
  args: {
    label: '任意の URL',
    value: '',
    placeholder: 'https://example.com（任意）',
  },
};
