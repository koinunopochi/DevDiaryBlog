import { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import UsernamePage from './UsernamePage';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

// note: 実際のAPIクライアントのインスタンスを作成
const apiClient = new EnhancedApiClient(
  process.env.STORYBOOK_API_URL || 'http://localhost:8080',
  process.env.CSRF_ENDPOINT || '/sanctum/csrf-cookie'
);

const meta: Meta<typeof UsernamePage> = {
  title: 'Pages/UsernamePage',
  component: UsernamePage,
  parameters: {
    layout: 'fullscreen',
  },
  tags: ['autodocs'],
  decorators: [
    (Story) => (
      <MemoryRouter initialEntries={['/user/johndoe']}>
        <Routes>
          <Route path="/user/:username" element={<Story />} />
        </Routes>
      </MemoryRouter>
    ),
  ],
};

export default meta;
type Story = StoryObj<typeof UsernamePage>;

export const Default: Story = {
  args: {
    apiClient,
  },
};

export const DifferentUser: Story = {
  args: {
    apiClient,
  },
  decorators: [
    (Story) => (
      <MemoryRouter initialEntries={['/user/janedoe']}>
        <Routes>
          <Route path="/user/:username" element={<Story />} />
        </Routes>
      </MemoryRouter>
    ),
  ],
};

