import React, { useState, useCallback, useEffect } from 'react';
import Input from '@components/atoms/form/input/Input';
import RequirementItem from '@components/atoms/requirement/RequirementItem';

interface InputEmailProps {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
}

const InputEmail: React.FC<InputEmailProps> = ({
  initialValue = '',
  onInputChange,
}) => {
  const [inputValue, setInputValue] = useState(initialValue);
  const [requirements, setRequirements] = useState({
    notEmpty: false,
    hasAtSymbol: false,
    hasValidDomain: false,
    noInvalidChars: false,
  });
  const [hasInteracted, setHasInteracted] = useState(false);

  const validateEmail = useCallback((value: string): string | null => {
    const newRequirements = {
      notEmpty: value.trim().length > 0,
      hasAtSymbol: value.includes('@'),
      hasValidDomain: /^[^@]+@[^@]+\.[^@]+$/.test(value),
      noInvalidChars:
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
          value
        ),
    };
    setRequirements(newRequirements);

    return Object.values(newRequirements).every(Boolean)
      ? null
      : 'メールアドレスは全ての要件を満たす必要があります';
  }, []);

  const handleInputChange = useCallback(
    (value: string, isValid: boolean) => {
      setInputValue(value);
      validateEmail(value);
      if (onInputChange) {
        onInputChange(value, isValid);
      }
      if (!hasInteracted && value !== '') {
        setHasInteracted(true);
      }
    },
    [onInputChange, validateEmail, hasInteracted]
  );

  useEffect(() => {
    validateEmail(initialValue);
    if (initialValue !== '') {
      setHasInteracted(true);
    }
  }, [initialValue, validateEmail]);

  const isInitial = !hasInteracted;

  return (
    <div className="relative">
      <Input
        label="メールアドレス"
        initialValue={inputValue}
        onInputChange={handleInputChange}
        validate={validateEmail}
        placeholder="example@example.com"
        type="email"
        required
      />
      <ul
        className={`
          text-xs mt-2 space-y-1
          transition-all duration-300 ease-in-out
          ${!hasInteracted ? 'opacity-0 max-h-0 overflow-hidden' : 'opacity-100 max-h-96'}
        `}
      >
        <RequirementItem met={requirements.notEmpty} isInitial={isInitial}>
          空でないこと
        </RequirementItem>
        <RequirementItem met={requirements.hasAtSymbol} isInitial={isInitial}>
          '@'記号を含むこと
        </RequirementItem>
        <RequirementItem
          met={requirements.hasValidDomain}
          isInitial={isInitial}
        >
          有効なドメインを含むこと
        </RequirementItem>
        <RequirementItem
          met={requirements.noInvalidChars}
          isInitial={isInitial}
        >
          無効なEmail書式でないこと
        </RequirementItem>
      </ul>
    </div>
  );
};

export default InputEmail;
