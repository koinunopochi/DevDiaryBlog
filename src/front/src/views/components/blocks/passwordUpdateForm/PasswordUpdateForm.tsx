import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputPassword from '@components/atoms/form/inputPassword/InputPassword';

interface PasswordUpdateFormProps {
  onSubmit: (password: string) => void;
}

const PasswordUpdateForm: React.FC<PasswordUpdateFormProps> = React.memo(
  ({ onSubmit }) => {
    const [password, setPassword] = useState<string>('');
    const [isValid, setIsValid] = useState<boolean>(false);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setPassword(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          alert('有効なパスワードを入力してください。');
          return;
        }

        onSubmit(password);
      },
      [password, isValid, onSubmit]
    );

    const memoizedInputPassword = useMemo(
      () => <InputPassword value={password} onChange={handleInputChange} />,
      [password, handleInputChange]
    );

    return (
      <div className="min-w-[300px] max-w-[500px] mx-auto p-6 text-gray-800 dark:text-gray-200">
        <form onSubmit={handleSubmit} className="space-y-6">
          {memoizedInputPassword}
          <div className="flex justify-end pt-4">
            <button
              type="submit"
              className="flex items-center bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200"
            >
              <Save size={20} className="mr-2" />
              パスワードを更新
            </button>
          </div>
        </form>
      </div>
    );
  }
);

export default PasswordUpdateForm;
