import React from 'react';
import InputWithRequirements from '@components/atoms/form/inputWithRequirements/InputWithRequirements';

interface InputUrlProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
  placeholder?: string;
}

const InputUrl: React.FC<InputUrlProps> = ({
  label,
  initialValue = '',
  onInputChange,
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

  const validateUrl = (value: string): string | null => {
    if (value === '') return null;
    if (!urlRequirements[0].validator(value)) return 'URLが無効です。';
    if (!urlRequirements[1].validator(value))
      return 'URLは255文字以下で入力してください。';
    return null;
  };

  return (
    <InputWithRequirements
      label={label}
      initialValue={initialValue}
      onInputChange={onInputChange}
      requirements={urlRequirements}
      validate={validateUrl}
      placeholder={placeholder}
      {...props}
    />
  );
};

export default InputUrl;
