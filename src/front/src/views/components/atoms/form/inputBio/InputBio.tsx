import React from 'react';
import TextareaWithRequirements from '@components/atoms/form/textareaWithRequirements/TextareaWithRequirements';

interface InputBioProps {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
}

const InputBio: React.FC<InputBioProps> = ({
  initialValue = '',
  onInputChange,
}) => {
  const bioRequirements = [
    {
      key: 'maxLength',
      label: '500文字以下',
      validator: (value: string) => value.length <= 500,
    },
  ];

  const validateBio = (value: string): string | null => {
    return bioRequirements.every((req) => req.validator(value))
      ? null
      : '経歴・自己紹介は500文字以下で入力してください。';
  };

  return (
    <TextareaWithRequirements
      label="経歴・自己紹介"
      initialValue={initialValue}
      onTextareaChange={onInputChange}
      requirements={bioRequirements}
      validate={validateBio}
      placeholder="○○に興味があります！"
      rows={3}
      required
    />
  );
};

export default InputBio;
