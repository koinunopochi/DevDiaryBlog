import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputEmail from '@components/atoms/form/inputEmail/InputEmail';
import SubmitButton from '@components/atoms/submitButton/SubmitButton';

interface EmailUpdateFormProps {
  initialEmail?: string;
  onSubmit: (email: string) => Promise<void>;
}

const EmailUpdateForm: React.FC<EmailUpdateFormProps> = React.memo(
  ({ initialEmail = '', onSubmit }) => {
    const [email, setEmail] = useState<string>(initialEmail);
    const [isValid, setIsValid] = useState<boolean>(true);
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setEmail(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      async (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          alert('有効なメールアドレスを入力してください。');
          return;
        }

        setIsLoading(true);
        await onSubmit(email);
        setIsLoading(false);
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
      </div>
    );
  }
);

export default EmailUpdateForm;
