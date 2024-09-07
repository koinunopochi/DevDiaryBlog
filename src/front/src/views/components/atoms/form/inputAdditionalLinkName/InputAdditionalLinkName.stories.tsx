import React, { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import InputAdditionalLinkName from './InputAdditionalLinkName';

const meta: Meta<typeof InputAdditionalLinkName> = {
  title: 'atoms/form/InputAdditionalLinkName',
  component: InputAdditionalLinkName,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    label: { control: 'text' },
    value: { control: 'text' },
    onChange: { action: 'changed' },
  },
};

export default meta;
type Story = StoryObj<typeof InputAdditionalLinkName>;

const InputAdditionalLinkNameWrapper = (
  args: React.ComponentProps<typeof InputAdditionalLinkName>
) => {
  const [value, setValue] = useState(args.value || '');
  return (
    <InputAdditionalLinkName
      {...args}
      value={value}
      onChange={(newValue, isValid) => {
        setValue(newValue);
        args.onChange(newValue, isValid);
      }}
    />
  );
};

export const Default: Story = {
  render: (args) => <InputAdditionalLinkNameWrapper {...args} />,
  args: {
    label: 'リンク名',
    value: '',
  },
};

export const WithInitialValue: Story = {
  render: (args) => <InputAdditionalLinkNameWrapper {...args} />,
  args: {
    label: 'リンク名',
    value: 'My Link',
  },
};

export const WithCustomOnChange: Story = {
  render: (args) => <InputAdditionalLinkNameWrapper {...args} />,
  args: {
    label: 'リンク名',
    value: '',
    onChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const InvalidInput: Story = {
  render: (args) => <InputAdditionalLinkNameWrapper {...args} />,
  args: {
    label: 'リンク名',
    value:
      'This is a very long initial value that exceeds the maximum length of 50 characters and should trigger validation errors',
  },
};

export const EdgeCaseMinLength: Story = {
  render: (args) => <InputAdditionalLinkNameWrapper {...args} />,
  args: {
    label: 'リンク名',
    value: 'A',
  },
};

export const EdgeCaseMaxLength: Story = {
  render: (args) => <InputAdditionalLinkNameWrapper {...args} />,
  args: {
    label: 'リンク名',
    value: 'This is exactly fifty characters long 12345678901234',
  },
};
