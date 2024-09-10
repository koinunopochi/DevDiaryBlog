import React from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import { Save, Send, Trash } from 'lucide-react';

import SubmitButton from './SubmitButton';

const meta: Meta<typeof SubmitButton> = {
  title: 'atoms/SubmitButton',
  component: SubmitButton,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    icon: {
      options: ['Save', 'Send', 'Trash', 'None'],
      mapping: {
        Save: Save,
        Send: Send,
        Trash: Trash,
        None: undefined,
      },
      control: { type: 'select' },
    },
    isLoading: {
      control: 'boolean',
    },
  },
};

export default meta;

type Story = StoryObj<typeof SubmitButton>;

export const Default: Story = {
  args: {
    children: 'Submit',
  },
};

export const WithIcon: Story = {
  args: {
    icon: Save,
    children: 'Save Changes',
  },
};

export const Disabled: Story = {
  args: {
    children: 'Submit',
    disabled: true,
  },
};

export const Loading: Story = {
  args: {
    children: 'Submitting...',
    isLoading: true,
  },
};

export const LoadingWithIcon: Story = {
  args: {
    icon: Save,
    children: 'Saving...',
    isLoading: true,
  },
};

export const CustomClass: Story = {
  args: {
    children: 'Custom Style',
    className: 'bg-purple-500 hover:bg-purple-600',
  },
};

// Wrapper component for the WithForm story
const FormWrapper: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [isLoading, setIsLoading] = React.useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulate async process
    setIsLoading(false);
    alert('Form submitted!');
  };

  return (
    <form onSubmit={handleSubmit}>
      {React.Children.map(children, (child) =>
        React.isValidElement(child)
          ? React.cloneElement(child, { isLoading } as any)
          : child
      )}
    </form>
  );
};

// Interactive story to demonstrate form submission with loading state
export const WithForm: Story = {
  render: (args) => (
    <FormWrapper>
      <SubmitButton {...args} />
    </FormWrapper>
  ),
  args: {
    children: 'Submit Form',
    icon: Send,
  },
};
