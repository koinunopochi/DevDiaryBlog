import type { Meta, StoryObj } from '@storybook/react';
import InputAdditionalLinkName from './InputAdditionalLinkName';

const meta = {
  title: 'atoms/form/InputAdditionalLinkName',
  component: InputAdditionalLinkName,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onInputChange: { action: 'inputChanged' },
  },
} satisfies Meta<typeof InputAdditionalLinkName>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {},
};

export const WithInitialValue: Story = {
  args: {
    initialValue: 'My Link',
  },
};

export const WithCustomOnInputChange: Story = {
  args: {
    onInputChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const InvalidInput: Story = {
  args: {
    initialValue:
      'This is a very long initial value that exceeds the maximum length of 50 characters and should trigger validation errors',
  },
};

export const EdgeCaseMinLength: Story = {
  args: {
    initialValue: 'A',
  },
};

export const EdgeCaseMaxLength: Story = {
  args: {
    initialValue: 'This is exactly fifty characters long 12345678901234',
  },
};
