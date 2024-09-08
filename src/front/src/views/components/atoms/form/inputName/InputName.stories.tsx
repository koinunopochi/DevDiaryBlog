import type { Meta, StoryObj } from '@storybook/react';
import InputName from './InputName';

const meta: Meta<typeof InputName> = {
  title: 'atoms/form/InputName',
  component: InputName,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    initialValue: { control: 'text' },
    onInputChange: { action: 'onInputChange' },
  },
};

export default meta;
type Story = StoryObj<typeof meta>;

const mockCheckNameAvailability = async (name: string): Promise<boolean> => {
  // This is a mock function. In a real scenario, this would call an API.
  await new Promise((resolve) => setTimeout(resolve, 500)); // Simulate API delay
  console.log("問合せがありました")
  return !['admin', 'root', 'user'].includes(name.toLowerCase());
};

export const Default: Story = {
  args: {
    initialValue: '',
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithInitialValue: Story = {
  args: {
    initialValue: 'John_Doe',
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithInvalidInitialValue: Story = {
  args: {
    initialValue: 'a',
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithUnavailableName: Story = {
  args: {
    initialValue: 'admin',
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithCustomAvailabilityCheck: Story = {
  args: {
    initialValue: '',
    checkNameAvailability: async (name: string) => {
      await new Promise((resolve) => setTimeout(resolve, 1000)); // Longer delay
      return name.length > 5; // Example: only names longer than 5 characters are "available"
    },
  },
};
