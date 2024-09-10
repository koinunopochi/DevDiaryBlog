import React, { useState, useCallback, useEffect } from 'react';
import Input from '@components/atoms/form/input/Input';
import RequirementItem from '@components/atoms/requirement/RequirementItem';
import { Eye, EyeOff } from 'lucide-react';

interface Requirement {
  key: string;
  label: string;
  validator: (value: string) => boolean;
}

interface InputWithRequirementsProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  label: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
  requirements: Requirement[];
  validate: (value: string) => string | null;
  toggleVisibility?: boolean;
}

const InputWithRequirements: React.FC<InputWithRequirementsProps> = ({
  label,
  value,
  onChange,
  requirements,
  type = 'text',
  placeholder,
  validate,
  toggleVisibility = false,
  ...props
}) => {
  const [showPassword, setShowPassword] = useState(false);
  const [requirementStates, setRequirementStates] = useState<
    Record<string, boolean>
  >({});
  const [hasInteracted, setHasInteracted] = useState(false);

  const validateInput = useCallback(
    (inputValue: string) => {
      const newRequirementStates = requirements.reduce(
        (acc, req) => {
          acc[req.key] = req.validator(inputValue);
          return acc;
        },
        {} as Record<string, boolean>
      );
      setRequirementStates(newRequirementStates);
      return validate(inputValue);
    },
    [requirements, validate]
  );

  const handleInputChange = useCallback(
    (newValue: string, isValid: boolean) => {
      validateInput(newValue);
      onChange(newValue, isValid);
      if (!hasInteracted && newValue !== '') {
        setHasInteracted(true);
      }
    },
    [onChange, validateInput, hasInteracted]
  );

  const toggleShowPassword = () => {
    if (toggleVisibility) {
      setShowPassword(!showPassword);
    }
  };

  useEffect(() => {
    validateInput(value);
    if (value !== '') {
      setHasInteracted(true);
    }
  }, [value, validateInput]);

  const isInitial = !hasInteracted;

  return (
    <div className="relative">
      <Input
        {...props}
        label={label}
        value={value}
        onChange={handleInputChange}
        validate={validateInput}
        type={toggleVisibility ? (showPassword ? 'text' : 'password') : type}
        placeholder={placeholder}
        className={`${toggleVisibility ? 'pr-10' : ''} text-sm sm:text-base`}
      />
      {toggleVisibility && (
        <div className="absolute top-0 right-0 mt-0.5 mr-1">
          <button
            type="button"
            onClick={toggleShowPassword}
            className="absolute right-2 top-8 sm:top-11 text-gray-500 hover:text-gray-700 focus:outline-none"
          >
            {showPassword ? (
              <EyeOff className="h-4 w-4 sm:h-5 sm:w-5" />
            ) : (
              <Eye className="h-4 w-4 sm:h-5 sm:w-5" />
            )}
          </button>
        </div>
      )}
      <ul
        className={`
          text-xs sm:text-sm mt-2 space-y-1
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
