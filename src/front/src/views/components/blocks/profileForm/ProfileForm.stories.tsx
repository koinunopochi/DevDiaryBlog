import type { Meta, StoryObj } from '@storybook/react';
import ProfileForm from './ProfileForm';

const meta = {
  title: 'blocks/ProfileForm',
  component: ProfileForm,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
} satisfies Meta<typeof ProfileForm>;

export default meta;
type Story = StoryObj<typeof ProfileForm>;

export const EmptyForm: Story = {
  args: {
    onSubmit: (data) => console.log('Submitted data:', data),
  },
};

export const PrefilledForm: Story = {
  args: {
    initialData: {
      displayName: 'John Doe',
      bio: 'Web developer and open source enthusiast',
      avatarUrl: 'https://example.com/avatar.jpg',
      socialLinks: {
        twitter: 'https://twitter.com/johndoe',
        github: 'https://github.com/johndoe',
        linkedin: 'https://linkedin.com/in/johndoe',
      },
    },
    onSubmit: (data) => console.log('Submitted data:', data),
  },
};

export const PartiallyFilledForm: Story = {
  args: {
    initialData: {
      displayName: 'Jane Smith',
      bio: '',
      avatarUrl: '',
      socialLinks: {
        twitter: 'https://twitter.com/janesmith',
      },
    },
    onSubmit: (data) => console.log('Submitted data:', data),
  },
};

export const WithAdditionalLinks: Story = {
  args: {
    initialData: {
      displayName: 'Alex Johnson',
      bio: 'UX designer and tech blogger',
      avatarUrl: 'https://example.com/alex-avatar.jpg',
      socialLinks: {
        twitter: 'https://twitter.com/alexj',
        github: 'https://github.com/alexj',
        dribbble: 'https://dribbble.com/alexj',
        medium: 'https://medium.com/@alexj',
      },
    },
    onSubmit: (data) => console.log('Submitted data:', data),
  },
};
