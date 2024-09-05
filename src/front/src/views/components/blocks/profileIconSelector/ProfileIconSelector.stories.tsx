import type { Meta, StoryObj } from '@storybook/react';
import ProfileIconSelector from './ProfileIconSelector';
import { useState } from 'react';

const meta = {
  title: 'blocks/ProfileIconSelector',
  component: ProfileIconSelector,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    icons: { control: 'array' },
    selectedIcon: { control: 'text' },
    onSelectIcon: { action: 'icon selected' },
  },
} satisfies Meta<typeof ProfileIconSelector>;

export default meta;
type Story = StoryObj<typeof meta>;

const defaultIcons = [
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_102260_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_106670_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_106830_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_107620_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_107750_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_109030_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_110340_128.png',
  'http://localhost:9000/dev-diary-blog/profile-icons/defaults/icon_113150_128.png',
];

export const Default: Story = {
  args: {
    icons: defaultIcons,
    onSelectIcon: (icon) => alert(icon),
  },
};

export const WithSelectedIcon: Story = {
  args: {
    icons: defaultIcons,
    selectedIcon: defaultIcons[0],
    onSelectIcon: (icon) => console.log(icon),
  },
};

// Interactive example with state
export const Interactive: Story = {
  render: (args) => {
    const [selectedIcon, setSelectedIcon] = useState<string | undefined>(
      undefined
    );
    return (
      <div>
        <ProfileIconSelector
          {...args}
          selectedIcon={selectedIcon}
          onSelectIcon={(icon) => {
            setSelectedIcon(icon);
            args.onSelectIcon(icon);
          }}
        />
        <p style={{ marginTop: '1rem' }}>
          Selected Icon: {selectedIcon || 'None'}
        </p>
      </div>
    );
  },
  args: {
    icons: defaultIcons,
    onSelectIcon: (icon) => alert(icon),
  },
};

export const CustomLayout: Story = {
  render: (args) => (
    <div style={{ width: '300px' }}>
      <ProfileIconSelector {...args} />
    </div>
  ),
  args: {
    icons: defaultIcons,
    onSelectIcon: (icon) => alert(icon),
  },
};

export const HasErrorIcon: Story = {
  args: {
    icons: ['invalid-icon', ...defaultIcons],
    selectedIcon: defaultIcons[0],
    onSelectIcon: (icon) => console.log(icon),
  },
};
