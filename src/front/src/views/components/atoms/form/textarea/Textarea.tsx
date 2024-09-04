import React, { ChangeEvent, useState, useEffect } from 'react';

interface TextareaProps
  extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
  label?: string;
  initialValue?: string;
  onTextareaChange?: (value: string, isValid: boolean) => void;
  validate?: (value: string) => string | null;
}

const Textarea: React.FC<TextareaProps> = ({
  label,
  initialValue = '',
  onTextareaChange,
  validate,
  required,
  ...props
}) => {
  const [value, setValue] = useState(initialValue);
  const [error, setError] = useState<string | null>(null);
  const [isInitialRender, setIsInitialRender] = useState(true);

  const validateTextarea = (newValue: string) => {
    if (validate) {
      return validate(newValue);
    }
    return null;
  };

  const handleChange = (e: ChangeEvent<HTMLTextAreaElement>) => {
    const newValue = e.target.value;
    setValue(newValue);

    const validationError = validateTextarea(newValue);
    setError(validationError);

    if (onTextareaChange) {
      onTextareaChange(newValue, !validationError);
    }
    setIsInitialRender(false);
  };

  useEffect(() => {
    // 初期値のバリデーション
    const initialError = validateTextarea(initialValue);
    setError(initialError);
    if (onTextareaChange) {
      onTextareaChange(initialValue, !initialError);
    }
  }, [initialValue, validate, onTextareaChange]);

  return (
    <div className="mb-4">
      {label && (
        <label className="block text-gray-700 text-sm font-bold mb-2">
          {label}
          {required && <span className="text-red-500 ml-1">*</span>}
        </label>
      )}
      <textarea
        {...props}
        value={value}
        onChange={handleChange}
        required={required}
        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
          error && !isInitialRender ? 'border-red-500' : ''
        }`}
      />
      {error && !isInitialRender && (
        <p className="text-red-500 text-xs italic">{error}</p>
      )}
    </div>
  );
};

export default Textarea;
