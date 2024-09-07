import React, { useState, useCallback, useEffect } from 'react';
import Input from '@components/atoms/form/input/Input';
import RequirementItem from '@components/atoms/requirement/RequirementItem';
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
  const [requirements, setRequirements] = useState({
    length: false,
    lowercase: false,
    uppercase: false,
    number: false,
    symbol: false,
    containsOnlyAllowedChars: false,
  });
  const [hasInteracted, setHasInteracted] = useState(false);

  const validatePassword = useCallback((value: string): string | null => {
    const newRequirements = {
      length: value.length >= 12,
      lowercase: /[a-z]/.test(value),
      uppercase: /[A-Z]/.test(value),
      number: /[0-9]/.test(value),
      symbol: /[!@#$%^&*()\-_=+{};:,<.>]/.test(value),
      containsOnlyAllowedChars: /^[a-zA-Z0-9!@#$%^&*()\-_=+{};:,<.>]*$/.test(
        value
      ),
    };
    setRequirements(newRequirements);

    return Object.values(newRequirements).every(Boolean)
      ? null
      : 'パスワードは全ての要件を満たす必要があります';
  }, []);

  const handleInputChange = useCallback(
    (value: string, isValid: boolean) => {
      setInputValue(value);
      validatePassword(value);
      if (onInputChange) {
        onInputChange(value, isValid);
      }
      if (!hasInteracted && value !== '') {
        setHasInteracted(true);
      }
    },
    [onInputChange, validatePassword, hasInteracted]
  );

  const toggleShowPassword = () => {
    setShowPassword(!showPassword);
  };

  useEffect(() => {
    validatePassword(initialValue);
    if (initialValue !== '') {
      setHasInteracted(true);
    }
  }, [initialValue, validatePassword]);

  const isInitial = !hasInteracted;

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
        className="pr-10"
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
      <ul
        className={`
          text-xs mt-2 space-y-1
          transition-all duration-300 ease-in-out
          ${isInitial ? 'opacity-0 max-h-0 overflow-hidden' : 'opacity-100 max-h-96'}
        `}
      >
        <RequirementItem met={requirements.length} isInitial={isInitial}>
          12文字以上
        </RequirementItem>
        <RequirementItem met={requirements.lowercase} isInitial={isInitial}>
          半角小文字を含む
        </RequirementItem>
        <RequirementItem met={requirements.uppercase} isInitial={isInitial}>
          半角大文字を含む
        </RequirementItem>
        <RequirementItem met={requirements.number} isInitial={isInitial}>
          半角数字を含む
        </RequirementItem>
        <RequirementItem met={requirements.symbol} isInitial={isInitial}>
          半角記号を含む (!@#$%^&*()-_=+&#123;&#125;;:,&lt;&gt;)
        </RequirementItem>
        <RequirementItem
          met={requirements.containsOnlyAllowedChars}
          isInitial={isInitial}
        >
          使用可能な文字のみを含む
        </RequirementItem>
      </ul>
    </div>
  );
};

export default InputPassword;
