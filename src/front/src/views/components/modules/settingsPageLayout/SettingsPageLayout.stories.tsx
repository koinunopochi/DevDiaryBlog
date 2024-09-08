import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import SettingsPageLayout from './SettingsPageLayout';

const meta: Meta<typeof SettingsPageLayout> = {
  title: 'modules/SettingsPageLayout',
  component: SettingsPageLayout,
  parameters: {
    layout: 'fullscreen',
  },
  tags: ['autodocs'],
  decorators: [
    (Story) => (
      <MemoryRouter initialEntries={['/settings']}>
        <Routes>
          <Route path="*" element={<Story />} />
        </Routes>
      </MemoryRouter>
    ),
  ],
  argTypes: {
    title: { control: 'text' },
    children: { control: 'text' },
  },
};

export default meta;
type Story = StoryObj<typeof SettingsPageLayout>;

export const Default: Story = {
  args: {
    title: 'アカウント設定',
    children: <p>アカウント設定のコンテンツがここに表示されます。</p>,
  },
};

export const WithForm: Story = {
  args: {
    title: 'プロフィール設定',
    children: (
      <form>
        <div className="mb-4">
          <label
            htmlFor="name"
            className="block text-sm font-medium text-gray-700"
          >
            名前
          </label>
          <input
            type="text"
            id="name"
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <div className="mb-4">
          <label
            htmlFor="email"
            className="block text-sm font-medium text-gray-700"
          >
            メールアドレス
          </label>
          <input
            type="email"
            id="email"
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <button
          type="submit"
          className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          保存
        </button>
      </form>
    ),
  },
};

export const LongContent: Story = {
  args: {
    title: 'プライバシー設定',
    children: (
      <>
        <p className="mb-4">
          プライバシー設定では、あなたのアカウントのセキュリティと個人情報の管理方法を制御できます。
        </p>
        {Array.from({ length: 10 }).map((_, index) => (
          <div key={index} className="mb-4 p-4 bg-gray-100 rounded">
            <h2 className="text-lg font-semibold mb-2">設定項目 {index + 1}</h2>
            <p>この設定に関する詳細な説明と選択肢がここに表示されます。</p>
          </div>
        ))}
      </>
    ),
  },
};

export const CustomStyling: Story = {
  args: {
    title: 'カスタムスタイル',
    children: <p>カスタムスタイルを適用したコンテンツ</p>,
    className:
      'bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-white',
  },
};

const LoadingTemplate: React.FC<
  React.ComponentProps<typeof SettingsPageLayout>
> = (args) => {
  const [isLoading, setIsLoading] = React.useState(true);

  React.useEffect(() => {
    const timer = setTimeout(() => setIsLoading(false), 2000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <SettingsPageLayout {...args}>
      {isLoading ? (
        <div className="flex justify-center items-center h-64">
          <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-900"></div>
        </div>
      ) : (
        args.children
      )}
    </SettingsPageLayout>
  );
};

export const Loading: Story = {
  render: (args) => <LoadingTemplate {...args} />,
  args: {
    title: '設定の読み込み中',
    children: <p>設定内容がここに表示されます。</p>,
  },
};
