import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import ProfilePage from './ProfilePage';
import SettingsPageLayout from '@components/modules/settingsPageLayout/SettingsPageLayout';
import { ProfileFormData } from '@components/blocks/profileForm/ProfileForm';

const meta: Meta<typeof ProfilePage> = {
  title: 'pages/settings/ProfilePage',
  component: ProfilePage,
  parameters: {
    layout: 'fullscreen',
  },
  tags: ['autodocs'],
  decorators: [
    (Story) => (
      <MemoryRouter initialEntries={['/settings/profile']}>
        <Routes>
          <Route path="*" element={<Story />} />
        </Routes>
      </MemoryRouter>
    ),
  ],
  argTypes: {
    onSubmit: { action: 'onSubmit' },
  },
};

export default meta;
type Story = StoryObj<typeof ProfilePage>;

const defaultProfileIcons = [
  '/icons/profile1.png',
  '/icons/profile2.png',
  '/icons/profile3.png',
  '/icons/profile4.png',
  '/icons/profile5.png',
];

const sampleInitialData: ProfileFormData = {
  displayName: 'John Doe',
  bio: 'Web developer and open source enthusiast',
  avatarUrl: '/icons/profile1.png',
  socialLinks: {
    twitter: 'https://twitter.com/johndoe',
    github: 'https://github.com/johndoe',
  },
};

export const Default: Story = {
  args: {
    initialData: sampleInitialData,
    defaultProfileIcons: defaultProfileIcons,
  },
};

export const EmptyForm: Story = {
  args: {
    initialData: {
      displayName: '',
      bio: '',
      avatarUrl: '',
      socialLinks: {},
    },
    defaultProfileIcons: defaultProfileIcons,
  },
};

export const WithCustomSocialLinks: Story = {
  args: {
    initialData: {
      ...sampleInitialData,
      socialLinks: {
        ...sampleInitialData.socialLinks,
        linkedin: 'https://linkedin.com/in/johndoe',
        website: 'https://johndoe.com',
      },
    },
    defaultProfileIcons: defaultProfileIcons,
  },
};

const LoadingTemplate: React.FC<React.ComponentProps<typeof ProfilePage>> = (
  args
) => {
  const [isLoading, setIsLoading] = React.useState(true);

  React.useEffect(() => {
    const timer = setTimeout(() => setIsLoading(false), 2000);
    return () => clearTimeout(timer);
  }, []);

  if (isLoading) {
    return (
      <SettingsPageLayout title="プロフィール設定">
        <div className="flex justify-center items-center h-64">
          <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
      </SettingsPageLayout>
    );
  }

  return <ProfilePage {...args} />;
};
