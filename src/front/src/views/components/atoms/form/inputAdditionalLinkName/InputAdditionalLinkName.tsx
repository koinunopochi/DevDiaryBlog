import React from 'react';
import InputWithRequirements from '@components/atoms/form/inputWithRequirements/InputWithRequirements';

interface InputAdditionalLinkNameProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  label?: string;
  value: string;
  onChange: (value: string, isValid: boolean) => void;
}

const InputAdditionalLinkName: React.FC<InputAdditionalLinkNameProps> = ({
  label = 'リンク名',
  value,
  onChange,
  ...props
}) => {
  const additionalLinkNameRequirements = [
    {
      key: 'minLength',
      label: '1文字以上',
      validator: (value: string) => value.length >= 1,
    },
    {
      key: 'maxLength',
      label: '50文字以下',
      validator: (value: string) => value.length <= 50,
    },
  ];

  const validateAdditionalLinkName = (value: string): string | null => {
    return additionalLinkNameRequirements.every((req) => req.validator(value))
      ? null
      : '表示名は1文字以上50文字以下で入力してください。';
  };

  return (
    <InputWithRequirements
      label={label}
      value={value}
      onChange={onChange}
      requirements={additionalLinkNameRequirements}
      validate={validateAdditionalLinkName}
      placeholder="表示したい名前"
      required
      {...props}
    />
  );
};

export default InputAdditionalLinkName;
