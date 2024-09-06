import React, { useState, useCallback } from 'react';
import Input from '@components/atoms/form/input/Input';

interface InputEmailProps {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
}

const InputEmail: React.FC<InputEmailProps> = ({
  initialValue = '',
  onInputChange,
}) => {
  const [inputValue, setInputValue] = useState(initialValue);

  const validateEmail = useCallback((value: string): string | null => {
    if (!value) {
      return 'メールアドレスは必須です。';
    }
    if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(value)) {
      return '有効なメールアドレスを入力してください。';
    }
    return null;
  }, []);

  const handleInputChange = useCallback(
    (value: string, isValid: boolean) => {
      setInputValue(value);
      if (onInputChange) {
        onInputChange(value, isValid);
      }
    },
    [onInputChange]
  );

  return (
    <div className="relative">
      <Input
        label="Email"
        initialValue={inputValue}
        onInputChange={handleInputChange}
        validate={validateEmail}
        placeholder="example@example.com"
        type="email"
        required
      />
    </div>
  );
};

export default InputEmail;
