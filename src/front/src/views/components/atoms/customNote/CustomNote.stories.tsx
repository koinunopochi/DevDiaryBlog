import type { Meta, StoryObj } from '@storybook/react';
import CustomNote from './CustomNote';

const meta: Meta<typeof CustomNote> = {
  title: 'atoms/CustomNote',
  component: CustomNote,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    className: { control: 'text' },
    children: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof CustomNote>;

export const Info: Story = {
  args: {
    className: 'info',
    children: 'This is an informational note.',
  },
};

export const Warn: Story = {
  args: {
    className: 'warn',
    children: 'This is a warning note.',
  },
};

export const Alert: Story = {
  args: {
    className: 'alert',
    children: 'This is an alert note.',
  },
};

export const LongContent: Story = {
  args: {
    className: 'info',
    children:
      'This is a note with longer content. It demonstrates how the CustomNote component handles multiple lines of text. It can be useful for displaying more detailed information or instructions.',
  },
};

export const WithHTML: Story = {
  args: {
    className: 'info',
    children: (
      <>
        This note contains <strong>HTML content</strong>.
        <br />
        It can include <em>various elements</em>.
      </>
    ),
  },
};

export const CustomClassName: Story = {
  args: {
    className: 'info custom-class',
    children: 'This note has a custom class added.',
  },
};

export const EmptyContent: Story = {
  args: {
    className: 'info',
    children: '',
  },
};
