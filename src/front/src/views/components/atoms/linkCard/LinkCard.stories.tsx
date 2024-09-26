import type { Meta, StoryObj } from '@storybook/react';
import LinkCard from './LinkCard';

const meta: Meta<typeof LinkCard> = {
  title: 'atoms/LinkCard',
  component: LinkCard,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    url: { control: 'text' },
    imageUrl: { control: 'text' },
    title: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof LinkCard>;

export const Default: Story = {
  args: {
    url: 'https://example.com',
    imageUrl: 'https://via.placeholder.com/150',
    title: 'Example Website',
  },
};

export const WithoutImage: Story = {
  args: {
    url: 'https://example.com',
    imageUrl: null,
    title: 'Website without Image',
  },
};

export const WithoutTitle: Story = {
  args: {
    url: 'https://example.com',
    imageUrl: 'https://via.placeholder.com/150',
    title: null,
  },
};

export const LongTitle: Story = {
  args: {
    url: 'https://example.com',
    imageUrl: 'https://via.placeholder.com/150',
    title: 'This is a very long title that should be truncated after two lines of text to ensure proper display',
  },
};

export const LongUrl: Story = {
  args: {
    url: 'https://example.com/very/long/url/path/that/should/be/truncated/to/fit/within/the/card',
    imageUrl: 'https://via.placeholder.com/150',
    title: 'Website with Long URL',
  },
};

export const WithoutImageAndTitle: Story = {
  args: {
    url: 'https://example.com',
    imageUrl: null,
    title: null,
  },
};

export const DarkMode: Story = {
  args: {
    url: 'https://example.com',
    imageUrl: 'https://via.placeholder.com/150',
    title: 'Dark Mode Example',
  },
  parameters: {
    backgrounds: { default: 'dark' },
  },
};
