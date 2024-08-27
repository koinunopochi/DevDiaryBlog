import { Meta, StoryFn } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import LoginPage from './LoginPage';

export default {
  title: 'Components/LoginPage',
  component: LoginPage,
  decorators: [
    (Story) => (
      <MemoryRouter>
        <Story />
      </MemoryRouter>
    ),
  ],
} as Meta;

const Template: StoryFn<typeof LoginPage> = () => <LoginPage />;

export const Default = Template.bind({});
Default.args = {};
