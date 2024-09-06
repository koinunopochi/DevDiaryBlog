import type { Meta, StoryObj } from '@storybook/react';
import InputEmail from './InputEmail';

const meta = {
  title: 'atoms/form/InputEmail',
  component: InputEmail,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onInputChange: { action: 'onInputChange' },
  },
} satisfies Meta<typeof InputEmail>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    initialValue: '',
  },
};

export const WithInitialValue: Story = {
  args: {
    initialValue: 'john.doe@example.com',
  },
};

export const WithInvalidInitialValue: Story = {
  args: {
    initialValue: 'invalid-email',
  },
};

export const WithEmptyValue: Story = {
  args: {
    initialValue: '',
  },
};

export const WithLongEmail: Story = {
  args: {
    initialValue: 'very.long.email.address.that.is.still.valid@example.com',
  },
};

export const WithSpecialCharacters: Story = {
  args: {
    initialValue: 'user+tag@example.co.uk',
  },
};
