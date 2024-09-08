import { Meta, StoryObj } from '@storybook/react';
import RequirementItem from './RequirementItem';

const meta: Meta<typeof RequirementItem> = {
  title: 'atoms/RequirementItem',
  component: RequirementItem,
  tags: ['autodocs'],
  argTypes: {
    met: { control: 'boolean' },
    isInitial: { control: 'boolean' },
    children: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof RequirementItem>;

export const Initial: Story = {
  args: {
    met: false,
    isInitial: true,
    children: '12文字以上',
  },
};

export const Met: Story = {
  args: {
    met: true,
    isInitial: false,
    children: '小文字を含む',
  },
};

export const NotMet: Story = {
  args: {
    met: false,
    isInitial: false,
    children: '大文字を含む',
  },
};

export const LongText: Story = {
  args: {
    met: true,
    isInitial: false,
    children: '記号を含む (!@#$%^&*()-_=+{};:,<.>)',
  },
};

export const AllStates: Story = {
  render: () => (
    <ul>
      <RequirementItem met={false} isInitial={true}>
        初期状態
      </RequirementItem>
      <RequirementItem met={true} isInitial={false}>
        満たされた要件
      </RequirementItem>
      <RequirementItem met={false} isInitial={false}>
        満たされていない要件
      </RequirementItem>
    </ul>
  ),
};
