import { Meta, StoryObj } from '@storybook/react';
import Pagination from './Pagination';

const meta: Meta<typeof Pagination> = {
  title: 'atoms/Pagination',
  component: Pagination,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
};

export default meta;

type Story = StoryObj<typeof Pagination>;

export const Default: Story = {
  args: {
    currentPage: 6,
    totalPages: 2222,
    onPageChange: (page) => console.log(`Page changed to ${page}`),
  },
};

export const FirstPage: Story = {
  args: {
    currentPage: 1,
    totalPages: 2222,
    onPageChange: (page) => console.log(`Page changed to ${page}`),
  },
};

export const LastPage: Story = {
  args: {
    currentPage: 2222,
    totalPages: 2222,
    onPageChange: (page) => console.log(`Page changed to ${page}`),
  },
};

export const FewPages: Story = {
  args: {
    currentPage: 3,
    totalPages: 7,
    onPageChange: (page) => console.log(`Page changed to ${page}`),
  },
};

export const ManyPages: Story = {
  args: {
    currentPage: 50,
    totalPages: 100,
    onPageChange: (page) => console.log(`Page changed to ${page}`),
  },
};
