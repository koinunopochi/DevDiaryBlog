import React, {
  ChangeEvent,
  useState,
  useEffect,
  useCallback,
  useRef,
} from 'react';

export interface InputProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  label?: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
  validate?: (value: string) => string | null;
  className?: string;
  debounceTime?: number;
}

const Input: React.FC<InputProps> = ({
  label,
  value,
  onChange,
  validate,
  className = '',
  required,
  debounceTime = 300,
  ...props
}) => {
  const [error, setError] = useState<string | null>(null);
  const [isTouched, setIsTouched] = useState(false);
  const [localValue, setLocalValue] = useState(value);
  const timerRef = useRef<NodeJS.Timeout | null>(null);

  const validateInput = useCallback(
    (inputValue: string) => {
      if (validate) {
        return validate(inputValue);
      }
      return null;
    },
    [validate]
  );

  const debouncedOnChange = useCallback(
    (newValue: string) => {
      const validationError = validateInput(newValue);
      setError(validationError);
      onChange(newValue, !validationError);
    },
    [onChange, validateInput]
  );

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const newValue = e.target.value;
    setLocalValue(newValue);
    setIsTouched(true);

    if (timerRef.current) {
      clearTimeout(timerRef.current);
    }

    timerRef.current = setTimeout(() => {
      debouncedOnChange(newValue);
    }, debounceTime);
  };

  const handleBlur = () => {
    setIsTouched(true);
    if (timerRef.current) {
      clearTimeout(timerRef.current);
    }
    debouncedOnChange(localValue);
  };

  useEffect(() => {
    setLocalValue(value);
  }, [value]);

  useEffect(() => {
    return () => {
      if (timerRef.current) {
        clearTimeout(timerRef.current);
      }
    };
  }, []);

  return (
    <div className="mb-4">
      {label && (
        <label className="block text-gray-700 text-sm font-bold mb-2">
          {label}
          {required && <span className="text-red-500 ml-1">*</span>}
        </label>
      )}
      <input
        {...props}
        value={localValue}
        onChange={handleChange}
        onBlur={handleBlur}
        required={required}
        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
          error && isTouched ? 'border-red-500' : ''
        } ${className}`}
      />
      {error && isTouched && (
        <p className="text-red-500 text-xs italic">{error}</p>
      )}
    </div>
  );
};

export default Input;
