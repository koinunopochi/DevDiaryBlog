import React, { useState, useCallback, useEffect } from 'react';
import Input from '@components/atoms/form/input/Input';
import RequirementItem from '@components/atoms/requirement/RequirementItem';
import { Eye, EyeOff } from 'lucide-react';

interface Requirement {
  key: string;
  label: string;
  validator: (value: string) => boolean;
}

interface InputWithRequirementsProps {
  label: string;
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
  requirements: Requirement[];
  type?: string;
  placeholder?: string;
  validate: (value: string) => string | null;
  toggleVisibility?: boolean;
}

const InputWithRequirements: React.FC<InputWithRequirementsProps> = ({
  label,
  initialValue = '',
  onInputChange,
  requirements,
  type = 'text',
  placeholder,
  validate,
  toggleVisibility = false,
}) => {
  const [inputValue, setInputValue] = useState(initialValue);
  const [showPassword, setShowPassword] = useState(false);
  const [requirementStates, setRequirementStates] = useState<
    Record<string, boolean>
  >({});
  const [hasInteracted, setHasInteracted] = useState(false);

  const validateInput = useCallback(
    (value: string) => {
      const newRequirementStates = requirements.reduce(
        (acc, req) => {
          acc[req.key] = req.validator(value);
          return acc;
        },
        {} as Record<string, boolean>
      );
      setRequirementStates(newRequirementStates);
      return validate(value);
    },
    [requirements, validate]
  );

  const handleInputChange = useCallback(
    (value: string, isValid: boolean) => {
      setInputValue(value);
      validateInput(value);
      if (onInputChange) {
        onInputChange(value, isValid);
      }
      if (!hasInteracted && value !== '') {
        setHasInteracted(true);
      }
    },
    [onInputChange, validateInput, hasInteracted]
  );

  const toggleShowPassword = () => {
    if (toggleVisibility) {
      setShowPassword(!showPassword);
    }
  };

  useEffect(() => {
    validateInput(initialValue);
    if (initialValue !== '') {
      setHasInteracted(true);
    }
  }, [initialValue, validateInput]);

  const isInitial = !hasInteracted;

  return (
    <div className="relative">
      <Input
        label={label}
        initialValue={inputValue}
        onInputChange={handleInputChange}
        validate={validateInput}
        required
        type={toggleVisibility ? (showPassword ? 'text' : 'password') : type}
        placeholder={placeholder}
        className={toggleVisibility ? 'pr-10' : ''}
      />
      {toggleVisibility && (
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
      )}
      <ul
        className={`
          text-xs mt-2 space-y-1
          transition-all duration-300 ease-in-out
          ${isInitial ? 'opacity-0 max-h-0 overflow-hidden' : 'opacity-100 max-h-96'}
        `}
      >
        {requirements.map((req) => (
          <RequirementItem
            key={req.key}
            met={requirementStates[req.key]}
            isInitial={isInitial}
          >
            {req.label}
          </RequirementItem>
        ))}
      </ul>
    </div>
  );
};

export default InputWithRequirements;
