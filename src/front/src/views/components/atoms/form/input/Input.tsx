import React, { ChangeEvent, useState, useEffect } from 'react';

interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label?: string;
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
  validate?: (value: string) => string | null;
}

const Input: React.FC<InputProps> = ({
  label,
  initialValue = '',
  onInputChange,
  validate,
  ...props
}) => {
  const [value, setValue] = useState(initialValue);
  const [error, setError] = useState<string | null>(null);
  const [isInitialRender, setIsInitialRender] = useState(true);

  const validateInput = (newValue: string) => {
    if (validate) {
      return validate(newValue);
    }
    return null;
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const newValue = e.target.value;
    setValue(newValue);

    const validationError = validateInput(newValue);
    setError(validationError);

    if (onInputChange) {
      onInputChange(newValue, !validationError);
    }
    setIsInitialRender(false);
  };

  useEffect(() => {
    // 初期値のバリデーション
    const initialError = validateInput(initialValue);
    setError(initialError);
    if (onInputChange) {
      onInputChange(initialValue, !initialError);
    }
  }, [initialValue, validate, onInputChange]);

  return (
    <div className="mb-4">
      {label && (
        <label className="block text-gray-700 text-sm font-bold mb-2">
          {label}
        </label>
      )}
      <input
        {...props}
        value={value}
        onChange={handleChange}
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

export default Input;
