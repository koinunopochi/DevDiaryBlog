import React from 'react';
import InputWithRequirements from '@components/atoms/form/inputWithRequirements/InputWithRequirements';

interface InputDisplayNameProps {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
}

const InputDisplayName: React.FC<InputDisplayNameProps> = ({
  initialValue = '',
  onInputChange,
}) => {
  const displayNameRequirements = [
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

  const validateDisplayName = (value: string): string | null => {
    return displayNameRequirements.every((req) => req.validator(value))
      ? null
      : '表示名は1文字以上50文字以下で入力してください。';
  };

  return (
    <InputWithRequirements
      label="表示名"
      initialValue={initialValue}
      onInputChange={onInputChange}
      requirements={displayNameRequirements}
      validate={validateDisplayName}
      placeholder="ぶろぐたろう"
      required
    />
  );
};

export default InputDisplayName;
