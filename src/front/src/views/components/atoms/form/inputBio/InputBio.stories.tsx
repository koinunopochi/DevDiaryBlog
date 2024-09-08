import type { Meta, StoryObj } from '@storybook/react';
import InputBio from './InputBio';

const meta: Meta<typeof InputBio> = {
  title: 'atoms/form/InputBio',
  component: InputBio,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    value: { control: 'text' },
    onChange: { action: 'inputChanged' },
  },
};

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {},
};

export const WithInitialValue: Story = {
  args: {
    value:
      '私はソフトウェア開発者です。新しい技術を学ぶことが大好きです。',
  },
};

export const WithCustomOnInputChange: Story = {
  args: {
    onChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const MaxLengthEdgeCase: Story = {
  args: {
    value: ''.padEnd(500, 'あ'),
  },
};

export const ExceedsMaxLength: Story = {
  args: {
    value: ''.padEnd(501, 'あ'),
  },
};

export const MultilineInput: Story = {
  args: {
    value: '1行目\n2行目\n3行目',
  },
};
