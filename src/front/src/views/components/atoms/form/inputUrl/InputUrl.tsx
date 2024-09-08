import React, { useCallback } from 'react';
import InputWithRequirements from '@components/atoms/form/inputWithRequirements/InputWithRequirements';

interface InputUrlProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  label: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
  placeholder?: string;
}

const InputUrl: React.FC<InputUrlProps> = ({
  label,
  value,
  onChange,
  placeholder,
  ...props
}) => {
  const urlRequirements = [
    {
      key: 'validUrl',
      label: '有効なURL',
      validator: (value: string) => {
        if (value === '') return true;
        try {
          new URL(value);
          return true;
        } catch {
          return false;
        }
      },
    },
    {
      key: 'maxLength',
      label: '255文字以下',
      validator: (value: string) => value.length <= 255,
    },
  ];

  const validateUrl = useCallback((value: string): string | null => {
    if (value === '') return null;
    if (!urlRequirements[0].validator(value)) return 'URLが無効です。';
    if (!urlRequirements[1].validator(value))
      return 'URLは255文字以下で入力してください。';
    return null;
  }, []);

  return (
    <InputWithRequirements
      label={label}
      value={value}
      onChange={onChange}
      requirements={urlRequirements}
      validate={validateUrl}
      placeholder={placeholder}
      {...props}
    />
  );
};

export default InputUrl;
