import type { Meta, StoryObj } from '@storybook/react';
import BaseStatus from './BaseStatus';

const meta = {
  title: 'atoms/status/BaseStatus',
  component: BaseStatus,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    status: {
      control: {
        type: 'select',
        options: ['initial', 'loading', 'error', 'success'],
      },
    },
    size: { control: 'number' },
    className: { control: 'text' },
  },
} satisfies Meta<typeof BaseStatus>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Initial: Story = {
  args: {
    status: 'initial',
  },
};

export const Loading: Story = {
  args: {
    status: 'loading',
  },
};

export const Error: Story = {
  args: {
    status: 'error',
  },
};

export const Success: Story = {
  args: {
    status: 'success',
  },
};

export const CustomSize: Story = {
  args: {
    status: 'success',
    size: 40,
  },
};

export const CustomClass: Story = {
  args: {
    status: 'error',
    className: 'p-2 bg-gray-100 rounded-full',
  },
};
