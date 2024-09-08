import React, { useState, useCallback, useRef, useEffect } from 'react';
import { debounce } from 'lodash';

export interface TextareaProps
  extends Omit<React.TextareaHTMLAttributes<HTMLTextAreaElement>, 'onChange'> {
  label?: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
  validate?: (value: string) => string | null;
  className?: string;
  debounceTime?: number;
}

const Textarea: React.FC<TextareaProps> = React.memo(
  ({
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

    const debouncedValidateRef = useRef<ReturnType<typeof debounce>>();

    useEffect(() => {
      debouncedValidateRef.current = debounce((textareaValue: string) => {
        if (validate) {
          const validationError = validate(textareaValue);
          setError(validationError);
          onChange(textareaValue, !validationError);
        } else {
          onChange(textareaValue, true);
        }
      }, debounceTime);

      return () => {
        debouncedValidateRef.current?.cancel();
      };
    }, [onChange, validate, debounceTime]);

    const handleChange = useCallback(
      (e: React.ChangeEvent<HTMLTextAreaElement>) => {
        const newValue = e.target.value;
        setLocalValue(newValue);
        setIsTouched(true);
        debouncedValidateRef.current?.(newValue);
      },
      []
    );

    const handleBlur = useCallback(() => {
      setIsTouched(true);
      debouncedValidateRef.current?.flush();
    }, []);

    useEffect(() => {
      setLocalValue(value);
    }, [value]);

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
  }
);

Textarea.displayName = 'Textarea';

export default Textarea;
