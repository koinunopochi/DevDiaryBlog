import type { Meta, StoryObj } from '@storybook/react';
import InputUrl from './InputUrl';

const meta = {
  title: 'atoms/form/InputUrl',
  component: InputUrl,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    initialValue: { control: 'text' },
    onInputChange: { action: 'inputChanged' },
    placeholder: { control: 'text' },
  },
} satisfies Meta<typeof InputUrl>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    label: 'ウェブサイト URL',
    placeholder: 'https://example.com',
  },
};

export const WithInitialValue: Story = {
  args: {
    label: 'ブログ URL',
    initialValue: 'https://myblog.example.com',
    placeholder: 'https://yourblog.com',
  },
};

export const WithCustomOnInputChange: Story = {
  args: {
    label: 'SNS プロフィール URL',
    placeholder: 'https://social.example.com/profile',
    onInputChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const InvalidUrl: Story = {
  args: {
    label: 'ウェブサイト URL',
    initialValue: 'not-a-valid-url',
    placeholder: 'https://example.com',
  },
};

export const MaxLengthEdgeCase: Story = {
  args: {
    label: '長い URL',
    initialValue: `https://example.com/${'a'.repeat(230)}`,
    placeholder: 'https://example.com',
  },
};

export const ExceedsMaxLength: Story = {
  args: {
    label: '長すぎる URL',
    initialValue: `https://example.com/${'a'.repeat(231)}`,
    placeholder: 'https://example.com',
  },
};

export const EmptyInput: Story = {
  args: {
    label: '任意の URL',
    initialValue: '',
    placeholder: 'https://example.com（任意）',
  },
};
