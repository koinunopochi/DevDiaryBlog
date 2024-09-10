import React from 'react';
import { Meta, StoryObj } from '@storybook/react';
import Toast, { ToastProps } from './Toast';
import { Info } from 'lucide-react';

const meta: Meta<typeof Toast> = {
  title: 'atoms/Toast',
  component: Toast,
  parameters: {
    layout: 'centered',
  },
  tags: ['autodocs'],
  argTypes: {
    message: { control: 'text' },
    type: {
      control: { type: 'select', options: ['success', 'error', 'custom'] },
    },
    duration: { control: 'number' },
    onClose: { action: 'closed' },
  },
};

export default meta;
type Story = StoryObj<typeof Toast>;

export const Success: Story = {
  args: {
    message: '操作が成功しました',
    type: 'success',
    duration: 3000,
    onClose: () => console.log('Toast closed'),
  },
};

export const Error: Story = {
  args: {
    message: 'エラーが発生しました',
    type: 'error',
    duration: 3000,
    onClose: () => console.log('Toast closed'),
  },
};

export const Custom: Story = {
  args: {
    message: 'カスタムメッセージ',
    type: 'custom',
    duration: 3000,
    onClose: () => console.log('Toast closed'),
  },
};

export const LongMessage: Story = {
  args: {
    message:
      'これは非常に長いメッセージです。トーストの表示がどのように調整されるか確認するためのものです。',
    type: 'success',
    duration: 5000,
    onClose: () => console.log('Toast closed'),
  },
};

export const NoDuration: Story = {
  args: {
    message: '手動で閉じる必要があります',
    type: 'custom',
    onClose: () => console.log('Toast closed'),
  },
};

const CustomIconTemplate: React.FC<ToastProps> = (args) => (
  <Toast
    {...args}
    message={
      <div className="flex items-center">
        <Info className="w-5 h-5 mr-2" />
        <span>{args.message}</span>
      </div>
    }
  />
);

export const CustomIcon: Story = {
  render: (args) => <CustomIconTemplate {...args} />,
  args: {
    message: 'カスタムアイコン付きメッセージ',
    type: 'custom',
    duration: 3000,
    onClose: () => console.log('Toast closed'),
  },
};

const MultipleToastsTemplate: React.FC = () => {
  const [toasts, setToasts] = React.useState<
    Array<{ id: number; props: ToastProps }>
  >([]);

  const addToast = (props: Omit<ToastProps, 'onClose'>) => {
    const id = Date.now();
    setToasts((prev) => [
      ...prev,
      { id, props: { ...props, onClose: () => removeToast(id) } },
    ]);
    if (props.duration) {
      setTimeout(() => {
        removeToast(id);
      }, props.duration);
    }
  };

  const removeToast = (id: number) => {
    setToasts((prev) => prev.filter((toast) => toast.id !== id));
  };

  return (
    <div>
      <button
        onClick={() =>
          addToast({
            message: '成功メッセージ',
            type: 'success',
            duration: 3000,
          })
        }
      >
        成功トースト追加
      </button>
      <button
        onClick={() =>
          addToast({
            message: 'エラーメッセージ',
            type: 'error',
            duration: 3000,
          })
        }
      >
        エラートースト追加
      </button>
      <button
        onClick={() =>
          addToast({
            message: 'カスタムメッセージ',
            type: 'custom',
            duration: 3000,
          })
        }
      >
        カスタムトースト追加
      </button>
      <div className="fixed bottom-4 right-4 space-y-2">
        {toasts.map(({ id, props }) => (
          <Toast key={id} {...props} />
        ))}
      </div>
    </div>
  );
};

export const MultipleToasts: Story = {
  render: () => <MultipleToastsTemplate />,
};
