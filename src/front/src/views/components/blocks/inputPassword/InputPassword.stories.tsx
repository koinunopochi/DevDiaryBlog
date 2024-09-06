import type { Meta, StoryObj } from '@storybook/react';
import InputPassword from './InputPassword';

const meta = {
  title: 'blocks/InputPassword',
  component: InputPassword,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onInputChange: { action: 'onInputChange' },
  },
} satisfies Meta<typeof InputPassword>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    initialValue: '',
  },
};

export const WithValidPassword: Story = {
  args: {
    initialValue: 'ValidP@ssw0rd123',
  },
};

export const WithInvalidPassword: Story = {
  args: {
    initialValue: 'weak',
  },
};

export const WithoutLowercase: Story = {
  args: {
    initialValue: 'NOLOWERCASE123@',
  },
};

export const WithoutUppercase: Story = {
  args: {
    initialValue: 'nouppercase123@',
  },
};

export const WithoutNumber: Story = {
  args: {
    initialValue: 'NoNumberHere@',
  },
};

export const WithoutSymbol: Story = {
  args: {
    initialValue: 'NoSymbolHere123',
  },
};

export const TooShort: Story = {
  args: {
    initialValue: 'Short1@',
  },
};
