import React, { useState, useCallback } from 'react';
import Input from '@components/atoms/form/input/Input';
import { Eye, EyeOff } from 'lucide-react';

interface InputPasswordProps {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
}

const InputPassword: React.FC<InputPasswordProps> = ({
  initialValue = '',
  onInputChange,
}) => {
  const [inputValue, setInputValue] = useState(initialValue);
  const [showPassword, setShowPassword] = useState(false);

  const validatePassword = useCallback((value: string): string | null => {
    if (value.length < 12) {
      return 'パスワードは12文字以上で入力してください';
    }
    if (!/[a-z]/.test(value)) {
      return 'パスワードには小文字が含まれている必要があります';
    }
    if (!/[A-Z]/.test(value)) {
      return 'パスワードには大文字が含まれている必要があります';
    }
    if (!/[0-9]/.test(value)) {
      return 'パスワードには数字が含まれている必要があります';
    }
    if (!/[!@#$%^&*()\-_=+{};:,<.>]/.test(value)) {
      return 'パスワードには記号が含まれている必要があります';
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

  const toggleShowPassword = () => {
    setShowPassword(!showPassword);
  };

  return (
    <div className="relative">
      <Input
        label="パスワード"
        initialValue={inputValue}
        onInputChange={handleInputChange}
        validate={validatePassword}
        required
        type={showPassword ? 'text' : 'password'}
        placeholder="Enter your password"
        className='pr-10'
      />
      <div className="absolute top-0 right-0 mt-0.5 mr-1">
        <button
          type="button"
          onClick={toggleShowPassword}
          className="absolute right-2 top-9 text-gray-500 hover:text-gray-700 focus:outline-none"
        >
          {showPassword ? (
            <EyeOff className="h-5 w-5" />
          ) : (
            <Eye className="h-5 w-5" />
          )}
        </button>
      </div>
      <ul className="text-xs mt-2 text-gray-600">
        <li>12文字以上</li>
        <li>小文字を含む</li>
        <li>大文字を含む</li>
        <li>数字を含む</li>
        <li>記号を含む (!@#$%^&*()-_=+&#123;&#125;;:,&lt;&gt;)</li>
      </ul>
    </div>
  );
};

export default InputPassword;
