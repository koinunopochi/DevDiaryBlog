import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import AccountPage from './AccountPage';

const meta: Meta<typeof AccountPage> = {
  title: 'pages/settings/AccountPage',
  component: AccountPage,
  parameters: {
    layout: 'fullscreen',
  },
  tags: ['autodocs'],
  decorators: [
    (Story) => (
      <MemoryRouter initialEntries={['/settings/account']}>
        <Routes>
          <Route path="*" element={<Story />} />
        </Routes>
      </MemoryRouter>
    ),
  ],
  argTypes: {
    initialEmail: { control: 'text' },
    initialName: { control: 'text' },
    onEmailSubmit: { action: 'onEmailSubmit' },
    onPasswordSubmit: { action: 'onPasswordSubmit' },
    onNameSubmit: { action: 'onNameSubmit' },
    checkNameAvailability: { action: 'checkNameAvailability' },
  },
};

export default meta;
type Story = StoryObj<typeof AccountPage>;

export const Default: Story = {
  args: {
    initialEmail: 'user@example.com',
    initialName: 'John Doe',
    checkNameAvailability: async () => true,
  },
};

export const EmptyFields: Story = {
  args: {
    checkNameAvailability: async () => true,
  },
};

export const NameUnavailable: Story = {
  args: {
    initialEmail: 'user@example.com',
    initialName: 'John Doe',
    checkNameAvailability: async () => false,
  },
};

export const CustomStyling: Story = {
  args: {
    ...Default.args,
  },
};

const LoadingTemplate: React.FC<React.ComponentProps<typeof AccountPage>> = (
  args
) => {
  const [isLoading, setIsLoading] = React.useState(true);

  React.useEffect(() => {
    const timer = setTimeout(() => setIsLoading(false), 2000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <div>
      {isLoading ? (
        <div className="flex justify-center items-center h-screen">
          <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
      ) : (
        <AccountPage {...args} />
      )}
    </div>
  );
};

export const Loading: Story = {
  render: (args) => <LoadingTemplate {...args} />,
  args: {
    ...Default.args,
  },
};
