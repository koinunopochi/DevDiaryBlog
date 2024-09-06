import React, { useState, useCallback, useEffect, useMemo } from 'react';
import Input from '@components/atoms/form/input/Input';
import { debounce } from 'lodash';
import { Loader2, CheckCircle2, XCircle } from 'lucide-react';

interface InputNameProps {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
  checkNameAvailability: (name: string) => Promise<boolean>;
}

const InputName: React.FC<InputNameProps> = ({
  initialValue = '',
  onInputChange,
  checkNameAvailability,
}) => {
  const [inputValue, setInputValue] = useState(initialValue);
  const [isAvailable, setIsAvailable] = useState<boolean | null>(null);
  const [isChecking, setIsChecking] = useState(false);

  const validateName = useCallback((value: string): string | null => {
    if (value.length < 3) {
      return '名前は3文字以上である必要があります。';
    }
    if (value.length > 20) {
      return '名前は20文字以下である必要があります。';
    }
    if (!/^[a-zA-Z0-9_]+$/.test(value)) {
      return '名前は半角英数字とアンダースコアのみ使用できます。';
    }
    return null;
  }, []);

  const checkAvailability = useCallback(
    async (name: string) => {
      if (
        name.length >= 3 &&
        name.length <= 20 &&
        /^[a-zA-Z0-9_]+$/.test(name)
      ) {
        setIsChecking(true);
        try {
          const available = await checkNameAvailability(name);
          setIsAvailable(available);
        } catch (error) {
          console.error('Error checking availability:', error);
          setIsAvailable(null);
        } finally {
          setIsChecking(false);
        }
      } else {
        setIsAvailable(null);
      }
    },
    [checkNameAvailability]
  );

  const debouncedCheckAvailability = useMemo(
    () =>
      debounce((value: string) => {
        checkAvailability(value);
      }, 300),
    [checkAvailability]
  );

  useEffect(() => {
    return () => {
      debouncedCheckAvailability.cancel();
    };
  }, [debouncedCheckAvailability]);

  const handleInputChange = useCallback(
    (value: string, isValid: boolean) => {
      setInputValue(value);
      debouncedCheckAvailability(value);
      if (onInputChange) {
        onInputChange(value, isValid);
      }
    },
    [debouncedCheckAvailability, onInputChange]
  );

  return (
    <div className="relative">
      <Input
        label="Name"
        initialValue={inputValue}
        onInputChange={handleInputChange}
        validate={validateName}
        required
        placeholder="user_name"
      />
      <div className="absolute top-0 right-0 mt-9 mr-2">
        {isChecking && (
          <Loader2 className="h-5 w-5 text-blue-500 animate-spin" />
        )}
        {!isChecking && isAvailable === false && (
          <XCircle className="h-5 w-5 text-red-500" />
        )}
        {!isChecking && isAvailable === true && (
          <CheckCircle2 className="h-5 w-5 text-green-500" />
        )}
      </div>
      {!isChecking && isAvailable === false && (
        <p className="text-red-500 text-xs italic mt-1">
          既に使われているユーザー名です
        </p>
      )}
      {!isChecking && isAvailable === true && (
        <p className="text-green-500 text-xs italic mt-1">
          利用可能なユーザー名です
        </p>
      )}
    </div>
  );
};

export default InputName;
