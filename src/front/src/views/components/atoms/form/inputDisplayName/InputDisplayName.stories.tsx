import React, { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import InputDisplayName from './InputDisplayName';

const meta: Meta<typeof InputDisplayName> = {
  title: 'atoms/form/InputDisplayName',
  component: InputDisplayName,
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
type Story = StoryObj<typeof InputDisplayName>;

const InputDisplayNameWrapper = (
  args: React.ComponentProps<typeof InputDisplayName>
) => {
  const [value, setValue] = useState(args.value || '');
  return (
    <InputDisplayName
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
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: '',
  },
};

export const WithInitialValue: Story = {
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: 'ブログ太郎',
  },
};

export const WithCustomOnChange: Story = {
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: '',
    onChange: (value: string, isValid: boolean) => {
      console.log(`Value: ${value}, Is Valid: ${isValid}`);
    },
  },
};

export const MinLengthEdgeCase: Story = {
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: 'あ',
  },
};

export const MaxLengthEdgeCase: Story = {
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: ''.padEnd(50, 'あ'),
  },
};

export const ExceedsMaxLength: Story = {
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: ''.padEnd(51, 'あ'),
  },
};

export const EmptyInput: Story = {
  render: (args) => <InputDisplayNameWrapper {...args} />,
  args: {
    label: '表示名',
    value: '',
  },
};
