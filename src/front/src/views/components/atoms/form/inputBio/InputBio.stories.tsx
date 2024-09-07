import type { Meta, StoryObj } from '@storybook/react';
import InputBio from './InputBio';

const meta = {
  title: 'atoms/form/InputBio',
  component: InputBio,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onInputChange: { action: 'inputChanged' },
  },
} satisfies Meta<typeof InputBio>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {},
};

export const WithInitialValue: Story = {
  args: {
    initialValue:
      '私はソフトウェア開発者です。新しい技術を学ぶことが大好きです。',
  },
};

export const WithCustomOnInputChange: Story = {
  args: {
    onInputChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const MaxLengthEdgeCase: Story = {
  args: {
    initialValue: ''.padEnd(500, 'あ'),
  },
};

export const ExceedsMaxLength: Story = {
  args: {
    initialValue: ''.padEnd(501, 'あ'),
  },
};

export const MultilineInput: Story = {
  args: {
    initialValue: '1行目\n2行目\n3行目',
  },
};
