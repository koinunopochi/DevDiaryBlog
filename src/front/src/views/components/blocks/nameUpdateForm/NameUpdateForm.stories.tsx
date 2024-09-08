import type { Meta, StoryObj } from '@storybook/react';

import NameUpdateForm from './NameUpdateForm';


const nameMeta: Meta<typeof NameUpdateForm> = {
  title: 'blocks/NameUpdateForm',
  component: NameUpdateForm,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default nameMeta;

type NameStory = StoryObj<typeof NameUpdateForm>;

const mockCheckNameAvailability = async (name: string): Promise<boolean> => {
  await new Promise((resolve) => setTimeout(resolve, 500)); // Simulate async process
  return name.toLowerCase() !== 'taken';
};

export const EmptyNameForm: NameStory = {
  args: {
    onSubmit: (name) => console.log('Submitted name:', name),
    checkNameAvailability: mockCheckNameAvailability,
  },
};
