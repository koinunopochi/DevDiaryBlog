import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import SettingsSidebar from './SettingsSidebar';

const meta: Meta<typeof SettingsSidebar> = {
  title: 'blocks/SettingsSidebar',
  component: SettingsSidebar,
  parameters: {
    layout: 'centered',
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
    className: { control: 'text' },
    activeItemClassName: { control: 'text' },
    collapsedClassName: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof SettingsSidebar>;

export const Default: Story = {
  args: {},
};

export const CustomColors: Story = {
  args: {
    className: 'bg-indigo-100 text-indigo-800',
    activeItemClassName: 'bg-indigo-200 text-indigo-900',
    collapsedClassName: 'bg-indigo-50',
  },
};

const InitiallyCollapsedTemplate: React.FC<
  React.ComponentProps<typeof SettingsSidebar>
> = (args) => {
  const [isOpen, setIsOpen] = React.useState(false);
  return (
    <div>
      <button onClick={() => setIsOpen(!isOpen)}>Toggle Sidebar</button>
      <SettingsSidebar {...args} />
    </div>
  );
};

export const InitiallyCollapsed: Story = {
  render: (args) => <InitiallyCollapsedTemplate {...args} />,
};

const InteractiveSidebarTemplate: React.FC<
  React.ComponentProps<typeof SettingsSidebar>
> = (args) => {
  const [activeItem, setActiveItem] = React.useState<string | null>(null);
  return (
    <div>
      <SettingsSidebar {...args} />
      <div>Active Item: {activeItem || 'None'}</div>
    </div>
  );
};

export const InteractiveSidebar: Story = {
  render: (args) => <InteractiveSidebarTemplate {...args} />,
};

export const CustomItemRender: Story = {
  args: {
    className: 'bg-gray-100',
    activeItemClassName: 'bg-blue-500 text-white',
  },
};
