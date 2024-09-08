import type { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import LoginPage from './LoginPage';
import AuthService from '@/services/AuthService';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

const meta: Meta<typeof LoginPage> = {
  title: 'pages/LoginPage',
  component: LoginPage,
  parameters: {
    layout: 'fullscreen',
  },
  decorators: [
    (Story) => (
      <MemoryRouter>
        <Story />
      </MemoryRouter>
    ),
  ],
  tags: ['autodocs'],
};

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {
  args: {
    authService: new AuthService(
      new EnhancedApiClient('http://localhost:8080', '/sanctum/csrf-cookie')
    ),
  },
};
