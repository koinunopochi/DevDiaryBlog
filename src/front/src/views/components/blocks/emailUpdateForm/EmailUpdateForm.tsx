import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputEmail from '@components/atoms/form/inputEmail/InputEmail';

interface EmailUpdateFormProps {
  initialEmail?: string;
  onSubmit: (email: string) => void;
}

const EmailUpdateForm: React.FC<EmailUpdateFormProps> = React.memo(
  ({ initialEmail = '', onSubmit }) => {
    const [email, setEmail] = useState<string>(initialEmail);
    const [isValid, setIsValid] = useState<boolean>(true);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setEmail(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          alert('有効なメールアドレスを入力してください。');
          return;
        }

        onSubmit(email);
      },
      [email, isValid, onSubmit]
    );

    const memoizedInputEmail = useMemo(
      () => <InputEmail value={email} onChange={handleInputChange} />,
      [email, handleInputChange]
    );

    return (
      <div className="min-w-[300px] max-w-[500px] mx-auto p-6 text-gray-800 dark:text-gray-200">
        <form onSubmit={handleSubmit} className="space-y-6">
          {memoizedInputEmail}
          <div className="flex justify-end pt-4">
            <button
              type="submit"
              className="flex items-center bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200"
            >
              <Save size={20} className="mr-2" />
              メールアドレスを更新する
            </button>
          </div>
        </form>
      </div>
    );
  }
);

export default EmailUpdateForm;
