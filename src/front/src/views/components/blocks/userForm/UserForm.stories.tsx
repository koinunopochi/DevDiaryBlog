import type { Meta, StoryObj } from '@storybook/react';
import UserForm from './UserForm';

const meta: Meta<typeof UserForm> = {
  title: 'blocks/UserForm',
  component: UserForm,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default meta;
type Story = StoryObj<typeof UserForm>;

const mockCheckNameAvailability = async (
  name: string,
): Promise<boolean> => {
  await new Promise((resolve) => setTimeout(resolve, 500)); // Simulate async process
  return name.toLowerCase() !== 'taken';
};

export const Default: Story = {
  args: {
    onEmailSubmit: (email) => console.log('Email submitted:', email),
    onPasswordSubmit: (password) => console.log('Password submitted', password),
    onNameSubmit: (name) => console.log('Name submitted:', name),
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithInitialValues: Story = {
  args: {
    initialEmail: 'user@example.com',
    initialName: 'John Doe',
    onEmailSubmit: (email) => console.log('Email submitted:', email),
    onPasswordSubmit: (password) => console.log('Password submitted', password),
    onNameSubmit: (name) => console.log('Name submitted:', name),
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithUnavailableName: Story = {
  args: {
    initialName: 'taken',
    onEmailSubmit: (email) => console.log('Email submitted:', email),
    onPasswordSubmit: (password) => console.log('Password submitted', password),
    onNameSubmit: (name) => console.log('Name submitted:', name),
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithCustomActions: Story = {
  args: {
    onEmailSubmit: (email) => alert(`Email submitted: ${email}`),
    onPasswordSubmit: (password) => alert('Password submitted' + password),
    onNameSubmit: (name) => alert(`Name submitted: ${name}`),
    checkNameAvailability: mockCheckNameAvailability,
  },
};

export const WithSlowNameCheck: Story = {
  args: {
    onEmailSubmit: (email) => console.log('Email submitted:', email),
    onPasswordSubmit: (password) => console.log('Password submitted', password),
    onNameSubmit: (name) => console.log('Name submitted:', name),
    checkNameAvailability: async () => {
      await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulate slow process
      return true;
    },
  },
};
