import type { Meta, StoryObj } from '@storybook/react';
import InputDisplayName from './InputDisplayName';

const meta = {
  title: 'atoms/form/InputDisplayName',
  component: InputDisplayName,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onInputChange: { action: 'inputChanged' },
  },
} satisfies Meta<typeof InputDisplayName>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {},
};

export const WithInitialValue: Story = {
  args: {
    initialValue: 'ブログ太郎',
  },
};

export const WithCustomOnInputChange: Story = {
  args: {
    onInputChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const MinLengthEdgeCase: Story = {
  args: {
    initialValue: 'あ',
  },
};

export const MaxLengthEdgeCase: Story = {
  args: {
    initialValue: ''.padEnd(50, 'あ'),
  },
};

export const ExceedsMaxLength: Story = {
  args: {
    initialValue: ''.padEnd(51, 'あ'),
  },
};

export const EmptyInput: Story = {
  args: {
    initialValue: '',
  },
};
