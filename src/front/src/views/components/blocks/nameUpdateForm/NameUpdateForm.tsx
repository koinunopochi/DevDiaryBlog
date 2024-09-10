import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputName from '@components/atoms/form/inputName/InputName';
import SubmitButton from '@components/atoms/submitButton/SubmitButton';
import Toast from '@components/atoms/toast/Toast';

interface NameUpdateFormProps {
  initialName?: string;
  onSubmit: (name: string) => Promise<void>;
  checkNameAvailability: (name: string) => Promise<boolean>;
}

const NameUpdateForm: React.FC<NameUpdateFormProps> = React.memo(
  ({ initialName = '', onSubmit, checkNameAvailability }) => {
    const [name, setName] = useState<string>(initialName);
    const [isValid, setIsValid] = useState<boolean>(true);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [toast, setToast] = useState<{
      message: string;
      type: 'success' | 'error' | 'custom';
    } | null>(null);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setName(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      async (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          setToast({
            message: '有効な名前を入力してください。',
            type: 'error',
          });
          return;
        }

        setIsLoading(true);
        try {
          const isAvailable = await checkNameAvailability(name);
          if (!isAvailable) {
            setToast({
              message: 'この名前は既に使用されています。',
              type: 'error',
            });
            setIsLoading(false);
            return;
          }

          await onSubmit(name);
          setToast({
            message: '名前が正常に更新されました。',
            type: 'success',
          });
        } catch (error: any) {
          console.warn('エラーが発生しました', error.message);
          setToast({ message: `エラー: ${error.message}`, type: 'error' });
        } finally {
          setIsLoading(false);
        }
      },
      [name, isValid, onSubmit, checkNameAvailability]
    );

    const memoizedInputName = useMemo(
      () => (
        <InputName
          initialValue={name}
          onInputChange={handleInputChange}
          checkNameAvailability={checkNameAvailability}
        />
      ),
      [name, handleInputChange, checkNameAvailability]
    );

    return (
      <div className="mx-auto p-1 sm:p-6 text-gray-800 dark:text-gray-200">
        <form onSubmit={handleSubmit} className="space-y-4 sm:space-y-6">
          {memoizedInputName}
          <div className="flex justify-end pt-2 sm:pt-4">
            <SubmitButton icon={Save} disabled={!isValid} isLoading={isLoading}>
              名前を更新
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

export default NameUpdateForm;
