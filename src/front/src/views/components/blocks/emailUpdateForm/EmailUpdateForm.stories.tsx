import type { Meta, StoryObj } from '@storybook/react';
import EmailUpdateForm from './EmailUpdateForm';

const emailMeta: Meta<typeof EmailUpdateForm> = {
  title: 'blocks/EmailUpdateForm',
  component: EmailUpdateForm,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default emailMeta;

type EmailStory = StoryObj<typeof EmailUpdateForm>;

export const EmptyEmailForm: EmailStory = {
  args: {
    onSubmit: (email) => console.log('Submitted email:', email),
  },
};

export const PrefilledEmailForm: EmailStory = {
  args: {
    initialEmail: 'johndoe@example.com',
    onSubmit: (email) => console.log('Submitted email:', email),
  },
};

export const InvalidEmailForm: EmailStory = {
  args: {
    initialEmail: 'invalid-email',
    onSubmit: (email) => console.log('Submitted email:', email),
  },
};
