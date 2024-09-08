import React, { useState, useCallback, useEffect } from 'react';
import Textarea from '@components/atoms/form/textarea/Textarea';
import RequirementItem from '@components/atoms/requirement/RequirementItem';

interface Requirement {
  key: string;
  label: string;
  validator: (value: string) => boolean;
}

interface TextareaWithRequirementsProps
  extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
  label: string;
  initialValue?: string;
  onTextareaChange?: (value: string, isValid: boolean) => void;
  requirements: Requirement[];
  placeholder?: string;
  validate: (value: string) => string | null;
}

const TextareaWithRequirements: React.FC<TextareaWithRequirementsProps> = ({
  label,
  initialValue = '',
  onTextareaChange,
  requirements,
  placeholder,
  validate,
  className = '',
  ...props
}) => {
  const [textareaValue, setTextareaValue] = useState(initialValue);
  const [requirementStates, setRequirementStates] = useState<
    Record<string, boolean>
  >({});
  const [hasInteracted, setHasInteracted] = useState(false);

  const validateTextarea = useCallback(
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

  const handleTextareaChange = useCallback(
    (value: string, isValid: boolean) => {
      setTextareaValue(value);
      validateTextarea(value);
      if (onTextareaChange) {
        onTextareaChange(value, isValid);
      }
      if (!hasInteracted && value !== '') {
        setHasInteracted(true);
      }
    },
    [onTextareaChange, validateTextarea, hasInteracted]
  );

  const handleKeyDown = (event: React.KeyboardEvent<HTMLTextAreaElement>) => {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();

      // カーソル位置に改行を挿入
      const textarea = event.currentTarget;
      const cursorPosition = textarea.selectionStart;
      const textBeforeCursor = textarea.value.substring(0, cursorPosition);
      const textAfterCursor = textarea.value.substring(cursorPosition);

      const newValue = textBeforeCursor + '\n' + textAfterCursor;
      setTextareaValue(newValue);

      // カーソル位置を更新
      setTimeout(() => {
        textarea.selectionStart = textarea.selectionEnd = cursorPosition + 1;
      }, 0);

      // 値の変更をトリガー
      handleTextareaChange(newValue, true);
    }
  };

  useEffect(() => {
    validateTextarea(initialValue);
    if (initialValue !== '') {
      setHasInteracted(true);
    }
  }, [initialValue, validateTextarea]);

  const isInitial = !hasInteracted;

  return (
    <div className="relative">
      <Textarea
        label={label}
        initialValue={textareaValue}
        onTextareaChange={handleTextareaChange}
        validate={validateTextarea}
        placeholder={placeholder}
        className={`${className} ${props.required ? 'required' : ''}`}
        onKeyDown={handleKeyDown}
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
