import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputPassword from '@components/atoms/form/inputPassword/InputPassword';
import SubmitButton from '@components/atoms/submitButton/SubmitButton';
import Toast from '@components/atoms/toast/Toast';

interface PasswordUpdateFormProps {
  onSubmit: (password: string) => Promise<void>;
}

const PasswordUpdateForm: React.FC<PasswordUpdateFormProps> = React.memo(
  ({ onSubmit }) => {
    const [password, setPassword] = useState<string>('');
    const [isValid, setIsValid] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [toast, setToast] = useState<{
      message: string;
      type: 'success' | 'error' | 'custom';
    } | null>(null);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setPassword(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      async (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          setToast({
            message: '有効なパスワードを入力してください。',
            type: 'error',
          });
          return;
        }

        setIsLoading(true);
        try {
          await onSubmit(password);
          setToast({
            message: 'パスワードが正常に更新されました。',
            type: 'success',
          });
          setPassword(''); // パスワードをリセット
          setIsValid(false); // バリデーション状態をリセット
        } catch (error: any) {
          console.warn('エラーが発生しました', error.message);
          setToast({ message: `エラー: ${error.message}`, type: 'error' });
        } finally {
          setIsLoading(false);
        }
      },
      [password, isValid, onSubmit]
    );

    const memoizedInputPassword = useMemo(
      () => <InputPassword value={password} onChange={handleInputChange} />,
      [password, handleInputChange]
    );

    return (
      <div className="mx-auto p-1 sm:p-6 text-gray-800 dark:text-gray-200">
        <form onSubmit={handleSubmit} className="space-y-4 sm:space-y-6">
          {memoizedInputPassword}
          <div className="flex justify-end pt-2 sm:pt-4">
            <SubmitButton icon={Save} disabled={!isValid} isLoading={isLoading}>
              パスワードを更新
            </SubmitButton>
          </div>
        </form>
        {toast && (
          <Toast
            message={toast.message}
            type={toast.type}
            duration={5000}
            onClose={() => setToast(null)}
          />
        )}
      </div>
    );
  }
);

export default PasswordUpdateForm;
