import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import Sidebar from './Sidebar';
import { Home, Settings, User, Mail, Bell } from 'lucide-react';

const meta: Meta<typeof Sidebar> = {
  title: 'atoms/Sidebar',
  component: Sidebar,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  decorators: [
    (Story) => (
      <MemoryRouter initialEntries={['/']}>
        <Routes>
          <Route path="*" element={<Story />} />
        </Routes>
      </MemoryRouter>
    ),
  ],
  argTypes: {
    items: { control: 'object' },
    className: { control: 'text' },
    activeItemClassName: { control: 'text' },
    collapsedClassName: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof Sidebar>;

const defaultItems = [
  { name: 'ホーム', href: '/', icon: <Home size={18} /> },
  { name: '設定', href: '/settings', icon: <Settings size={18} /> },
  { name: 'プロフィール', href: '/profile', icon: <User size={18} /> },
  { name: 'メッセージ', href: '/messages', icon: <Mail size={18} /> },
  { name: '通知', href: '/notifications', icon: <Bell size={18} /> },
];

export const Default: Story = {
  args: {
    items: defaultItems,
  },
};

export const CustomColors: Story = {
  args: {
    items: defaultItems,
    className: 'bg-indigo-100 text-indigo-800',
    activeItemClassName: 'bg-indigo-200 text-indigo-900',
    collapsedClassName: 'bg-indigo-50',
  },
};

export const NoIcons: Story = {
  args: {
    items: defaultItems.map(({ icon, ...rest }) => rest),
  },
};

export const LongItemList: Story = {
  args: {
    items: [
      ...defaultItems,
      { name: 'ヘルプ', href: '/help', icon: <Home size={18} /> },
      { name: 'プライバシー', href: '/privacy', icon: <Settings size={18} /> },
      { name: '利用規約', href: '/terms', icon: <User size={18} /> },
      { name: 'お問い合わせ', href: '/contact', icon: <Mail size={18} /> },
      { name: 'ログアウト', href: '/logout', icon: <Bell size={18} /> },
    ],
  },
};

const InitiallyCollapsedTemplate: React.FC<
  React.ComponentProps<typeof Sidebar>
> = (args) => {
  const [isOpen, setIsOpen] = React.useState(false);
  return (
    <div>
      <button onClick={() => setIsOpen(!isOpen)}>Toggle Sidebar</button>
      <Sidebar {...args} />
    </div>
  );
};

export const InitiallyCollapsed: Story = {
  render: (args) => <InitiallyCollapsedTemplate {...args} />,
  args: {
    items: defaultItems,
  },
};

const InteractiveSidebarTemplate: React.FC<
  React.ComponentProps<typeof Sidebar>
> = (args) => {
  const [activeItem, setActiveItem] = React.useState<string | null>(null);
  const items = args.items.map((item) => ({
    ...item,
    onClick: () => setActiveItem(item.name),
  }));
  return (
    <div>
      <Sidebar {...args} items={items} />
      <div>Active Item: {activeItem || 'None'}</div>
    </div>
  );
};

export const InteractiveSidebar: Story = {
  render: (args) => <InteractiveSidebarTemplate {...args} />,
  args: {
    items: defaultItems,
  },
};

export const CustomItemRender: Story = {
  args: {
    items: defaultItems.map((item) => ({
      ...item,
      render: ({
        isActive,
        isOpen,
      }: {
        isActive: boolean;
        isOpen: boolean;
      }) => (
        <div className={`p-2 ${isActive ? 'font-bold' : ''}`}>
          {item.icon}
          {isOpen && <span className="ml-2">{item.name}</span>}
          {isActive && <span className="ml-2">●</span>}
        </div>
      ),
    })),
  },
};
