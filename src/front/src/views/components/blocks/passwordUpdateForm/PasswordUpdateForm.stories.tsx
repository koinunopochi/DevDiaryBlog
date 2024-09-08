import type { Meta, StoryObj } from '@storybook/react';
import PasswordUpdateForm from './PasswordUpdateForm';

const passwordMeta: Meta<typeof PasswordUpdateForm> = {
  title: 'blocks/PasswordUpdateForm',
  component: PasswordUpdateForm,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default passwordMeta;

type PasswordStory = StoryObj<typeof PasswordUpdateForm>;

export const EmptyPasswordForm: PasswordStory = {
  args: {
    onSubmit: (password) => console.log('Submitted password:', password),
  },
};
