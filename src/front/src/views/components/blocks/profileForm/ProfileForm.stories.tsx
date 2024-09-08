import type { Meta, StoryObj } from '@storybook/react';
import ProfileForm from './ProfileForm';

const meta: Meta<typeof ProfileForm> = {
  title: 'blocks/ProfileForm',
  component: ProfileForm,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default meta;
type Story = StoryObj<typeof ProfileForm>;

const defaultIcons = [
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_106670_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_106830_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_107620_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_107750_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_109030_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_110340_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_113150_128.png',
];

export const EmptyForm: Story = {
  args: {
    initialData: {
      displayName: '',
      bio: '',
      avatarUrl: '',
      socialLinks: {},
    },
    onSubmit: (data) => console.log('Submitted data:', data),
    defaultProfileIcons: defaultIcons,
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
    defaultProfileIcons: defaultIcons,
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
    defaultProfileIcons: defaultIcons,
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
    defaultProfileIcons: defaultIcons,
  },
};

export const HasErrorIcon: Story = {
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
    defaultProfileIcons: [...defaultIcons, 'invalid-icon'],
  },
};

export const WithError: Story = {
  args: {
    initialData: {
      displayName: 'a'.repeat(51),
      bio: 'a'.repeat(501),
      avatarUrl: 'ht',
      socialLinks: {
        twitter: 'h/alexj',
        github: 'https.com/alexj',
        dribbble: 'httpribbble.com/alexj',
        medium: 'httpedium.com/@alexj',
      },
    },
    onSubmit: (data) => console.log('Submitted data:', data),
    defaultProfileIcons: [...defaultIcons, 'invalid-icon'],
  },
};
