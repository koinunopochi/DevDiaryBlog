import React, { ChangeEvent, useState, useEffect, useCallback } from 'react';

interface TextareaProps
  extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
  label?: string;
  initialValue?: string;
  onTextareaChange?: (value: string, isValid: boolean) => void;
  validate?: (value: string) => string | null;
  className?:string;
}

const Textarea: React.FC<TextareaProps> = ({
  label,
  initialValue = '',
  onTextareaChange,
  validate,
  required,
  className='',
  ...props
}) => {
  const [value, setValue] = useState(initialValue);
  const [error, setError] = useState<string | null>(null);
  const [isInitialRender, setIsInitialRender] = useState(true);

  const validateTextarea = useCallback(
    (newValue: string) => {
      if (validate) {
        return validate(newValue);
      }
      return null;
    },
    [validate]
  );

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
    // 初期値のバリデーションと value state の更新
    const initialError = validateTextarea(initialValue);
    setError(initialError);
    setValue(initialValue); // initialValue が変更されたら value を更新
    if (onTextareaChange) {
      onTextareaChange(initialValue, !initialError);
    }
  }, [initialValue, validateTextarea, onTextareaChange]);

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
        } ${className}`}
      />
      {error && !isInitialRender && (
        <p className="text-red-500 text-xs italic">{error}</p>
      )}
    </div>
  );
};

export default Textarea;
