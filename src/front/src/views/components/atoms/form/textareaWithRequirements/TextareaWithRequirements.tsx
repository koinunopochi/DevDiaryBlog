import React, { useState, useCallback, useEffect } from 'react';
import Textarea from '@components/atoms/form/textarea/Textarea';
import RequirementItem from '@components/atoms/requirement/RequirementItem';

interface Requirement {
  key: string;
  label: string;
  validator: (value: string) => boolean;
}

interface TextareaWithRequirementsProps
  extends Omit<React.TextareaHTMLAttributes<HTMLTextAreaElement>, 'onChange'> {
  label: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
  requirements: Requirement[];
  validate: (value: string) => string | null;
  debounceTime?: number;
}

const TextareaWithRequirements: React.FC<TextareaWithRequirementsProps> = ({
  label,
  value,
  onChange,
  requirements,
  validate,
  debounceTime = 300,
  placeholder,
  ...props
}) => {
  const [requirementStates, setRequirementStates] = useState<
    Record<string, boolean>
  >({});
  const [hasInteracted, setHasInteracted] = useState(false);

  const validateTextarea = useCallback(
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

  const handleTextareaChange = useCallback(
    (newValue: string, isValid: boolean) => {
      validateTextarea(newValue);
      onChange(newValue, isValid);
      if (!hasInteracted && newValue !== '') {
        setHasInteracted(true);
      }
    },
    [onChange, validateTextarea, hasInteracted]
  );

  const handleKeyDown = (event: React.KeyboardEvent<HTMLTextAreaElement>) => {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();
      const textarea = event.currentTarget;
      const cursorPosition = textarea.selectionStart;
      const textBeforeCursor = textarea.value.substring(0, cursorPosition);
      const textAfterCursor = textarea.value.substring(cursorPosition);
      const newValue = textBeforeCursor + '\n' + textAfterCursor;
      onChange(newValue, true);
      setTimeout(() => {
        textarea.selectionStart = textarea.selectionEnd = cursorPosition + 1;
      }, 0);
    }
  };

  useEffect(() => {
    validateTextarea(value);
    if (value !== '') {
      setHasInteracted(true);
    }
  }, [value, validateTextarea]);

  const isInitial = !hasInteracted;

  return (
    <div className="relative">
      <Textarea
        label={label}
        value={value}
        onChange={handleTextareaChange}
        validate={validateTextarea}
        placeholder={placeholder}
        onKeyDown={handleKeyDown}
        debounceTime={debounceTime}
        {...props}
      />
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

export default TextareaWithRequirements;
