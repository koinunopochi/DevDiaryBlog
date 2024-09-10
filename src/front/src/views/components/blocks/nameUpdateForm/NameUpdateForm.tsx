import React, { useState, useCallback, useMemo } from 'react';
import { Save } from 'lucide-react';
import InputName from '@components/atoms/form/inputName/InputName';
import SubmitButton from '@components/atoms/submitButton/SubmitButton';

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

    const handleInputChange = useCallback((value: string, valid: boolean) => {
      setName(value);
      setIsValid(valid);
    }, []);

    const handleSubmit = useCallback(
      async (e: React.FormEvent) => {
        e.preventDefault();

        if (!isValid) {
          alert('有効な名前を入力してください。');
          return;
        }

        setIsLoading(true);
        await onSubmit(name);
        setIsLoading(false);
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
            <SubmitButton icon={Save} disabled={!isValid} isLoading={isLoading}>
              名前を更新
            </SubmitButton>
          </div>
        </form>
      </div>
    );
  }
);

export default NameUpdateForm;
