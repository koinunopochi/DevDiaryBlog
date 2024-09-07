import React from 'react';
import InputWithRequirements from '@components/atoms/form/inputWithRequirements/InputWithRequirements';

interface InputPasswordProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  label?: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
}

const InputPassword: React.FC<InputPasswordProps> = ({
  label = 'パスワード',
  value,
  onChange,
  ...props
}) => {
  const passwordRequirements = [
    {
      key: 'length',
      label: '12文字以上であること',
      validator: (value: string) => value.length >= 12,
    },
    {
      key: 'lowercase',
      label: '半角小文字を含むこと',
      validator: (value: string) => /[a-z]/.test(value),
    },
    {
      key: 'uppercase',
      label: '半角大文字を含むこと',
      validator: (value: string) => /[A-Z]/.test(value),
    },
    {
      key: 'number',
      label: '半角数字を含むこと',
      validator: (value: string) => /[0-9]/.test(value),
    },
    {
      key: 'symbol',
      label: '半角記号(!@#$%^&*()-_=+{};:,<>)を含むこと',
      validator: (value: string) => /[!@#$%^&*()\-_=+{};:,<.>]/.test(value),
    },
    {
      key: 'containsOnlyAllowedChars',
      label: '使用可能な文字のみを含むこと',
      validator: (value: string) =>
        /^[a-zA-Z0-9!@#$%^&*()\-_=+{};:,<.>]*$/.test(value),
    },
  ];

  const validatePassword = (value: string): string | null => {
    return passwordRequirements.every((req) => req.validator(value))
      ? null
      : 'パスワードは全ての要件を満たす必要があります';
  };

  return (
    <InputWithRequirements
      label={label}
      value={value}
      onChange={onChange}
      requirements={passwordRequirements}
      type="password"
      placeholder="Enter your password"
      validate={validatePassword}
      toggleVisibility
      required
      {...props}
    />
  );
};

export default InputPassword;
