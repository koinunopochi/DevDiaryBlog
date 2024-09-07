import type { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import RegisterPage from './Register';

const meta = {
  title: 'pages/RegisterPage',
  component: RegisterPage,
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
} satisfies Meta<typeof RegisterPage>;

export default meta;
type Story = StoryObj<typeof meta>;

export const Default: Story = {};
