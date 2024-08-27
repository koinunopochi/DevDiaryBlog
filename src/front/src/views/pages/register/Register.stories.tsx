import { Meta, StoryFn } from '@storybook/react';
import { MemoryRouter } from 'react-router-dom';
import RegisterPage from './Register';

export default {
  title: 'Components/RegisterPage',
  component: RegisterPage,
  decorators: [
    (Story) => (
      <MemoryRouter>
        <Story />
      </MemoryRouter>
    ),
  ],
} as Meta;

const Template: StoryFn<typeof RegisterPage> = () => <RegisterPage />;

export const Default = Template.bind({});
Default.args = {};
