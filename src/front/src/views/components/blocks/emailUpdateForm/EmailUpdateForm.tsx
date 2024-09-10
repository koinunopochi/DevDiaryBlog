import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputEmail from '@components/atoms/form/inputEmail/InputEmail';
import SubmitButton from '@components/atoms/submitButton/SubmitButton';
import Toast from '@components/atoms/toast/Toast';

interface EmailUpdateFormProps {
  initialEmail?: string;
  onSubmit: (email: string) => Promise<void>;
}

const EmailUpdateForm: React.FC<EmailUpdateFormProps> = React.memo(
  ({ initialEmail = '', onSubmit }) => {
    const [email, setEmail] = useState<string>(initialEmail);
    const [isValid, setIsValid] = useState<boolean>(true);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [toast, setToast] = useState<{
      message: string;
      type: 'success' | 'error' | 'custom';
    } | null>(null);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setEmail(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      async (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          setToast({
            message: '有効なメールアドレスを入力してください。',
            type: 'error',
          });
          return;
        }

        setIsLoading(true);
        try {
          await onSubmit(email);
          setToast({
            message: 'メールアドレスが正常に更新されました。',
            type: 'success',
          });
        } catch (error: any) {
          console.warn('エラーが発生しました', error.message);
          setToast({ message: `エラー: ${error.message}`, type: 'error' });
        } finally {
          setIsLoading(false);
        }
      },
      [email, isValid, onSubmit]
    );

    const memoizedInputEmail = useMemo(
      () => <InputEmail value={email} onChange={handleInputChange} />,
      [email, handleInputChange]
    );

    return (
      <div className="mx-auto p-1 sm:p-6 text-gray-800 dark:text-gray-200">
        <form onSubmit={handleSubmit} className="space-y-4 sm:space-y-6">
          {memoizedInputEmail}
          <div className="flex justify-end pt-2 sm:pt-4">
            <SubmitButton icon={Save} disabled={!isValid} isLoading={isLoading}>
              メールアドレスを更新
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

export default EmailUpdateForm;
