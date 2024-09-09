import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputName from '@components/atoms/form/inputName/InputName';

interface NameUpdateFormProps {
  initialName?: string;
  onSubmit: (name: string) => void;
  checkNameAvailability: (name: string) => Promise<boolean>;
}

const NameUpdateForm: React.FC<NameUpdateFormProps> = React.memo(
  ({ initialName = '', onSubmit, checkNameAvailability }) => {
    const [name, setName] = useState<string>(initialName);
    const [isValid, setIsValid] = useState<boolean>(true);

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setName(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          alert('有効な名前を入力してください。');
          return;
        }

        onSubmit(name);
      },
      [name, isValid, onSubmit]
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
            <button
              type="submit"
              className="w-full sm:w-auto flex items-center justify-center bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200 text-sm sm:text-base"
            >
              <Save size={18} className="mr-2" />
              名前を更新
            </button>
          </div>
        </form>
      </div>
    );
  }
);

export default NameUpdateForm;
