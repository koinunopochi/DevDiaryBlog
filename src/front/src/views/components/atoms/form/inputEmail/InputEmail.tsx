import React from 'react';
import InputWithRequirements from '@components/atoms/form/inputWithRequirements/InputWithRequirements';

interface InputEmailProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  label?: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
}

const InputEmail: React.FC<InputEmailProps> = ({
  label = 'メールアドレス',
  value,
  onChange,
  ...props
}) => {
  const emailRequirements = [
    {
      key: 'notEmpty',
      label: '空でないこと',
      validator: (value: string) => value.trim().length > 0,
    },
    {
      key: 'hasAtSymbol',
      label: '@記号を含むこと',
      validator: (value: string) => value.includes('@'),
    },
    {
      key: 'hasValidDomain',
      label: '有効なドメインを含むこと',
      validator: (value: string) => /^[^@]+@[^@]+\.[^@]+$/.test(value),
    },
    {
      key: 'noInvalidChars',
      label: '有効なメールアドレス形式であること',
      validator: (value: string) =>
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
          value
        ),
    },
  ];

  const validateEmail = (value: string): string | null => {
    return emailRequirements.every((req) => req.validator(value))
      ? null
      : 'メールアドレスは全ての要件を満たす必要があります';
  };

  return (
    <InputWithRequirements
      label={label}
      value={value}
      onChange={onChange}
      requirements={emailRequirements}
      type="email"
      placeholder="example@example.com"
      validate={validateEmail}
      required
      {...props}
    />
  );
};

export default InputEmail;
