export const isUrl = (url: string) => {
  try {
    new URL(url);
    return true;
  } catch (error: unknown) {
    return false;
  }
};

export const isRegex = (value: string, regex: RegExp) => {
  return regex.test(value);
};

export const isMaxTextLength = (value: string, maxLength: number) => {
  return value.length <= maxLength;
};

export const isMinTextLength = (value: string, minLength: number) => {
  return value.length >= minLength;
};

export const validateDisplayName = (value: string) => {
  const errorMessage = '表示名は1文字以上50文字以下で入力してください。';

  if (!isMinTextLength(value, 1)) return errorMessage;
  if (!isMaxTextLength(value, 50)) return errorMessage;
  return null;
};

export const validateUserBio = (value: string) => {
  const errorMessage = '経歴・自己紹介は500文字以下で入力してください。';

  if (!isMaxTextLength(value, 500)) return errorMessage;
  return null;
};

export const validateAdditionalLinkName = (value: string) => {
  const errorMessage =
    '表示名は1文字以上50文字以下で入力してください。';

  if (!isMinTextLength(value, 1)) return errorMessage;
  if (!isMaxTextLength(value, 50)) return errorMessage;
  return null;
};

export const validateUrl = (value: string) => {
  if (value === '') return null;
  if (!isUrl(value)) return 'URLが無効です。';
  if (!isMaxTextLength(value, 255)) return 'URLは150文字以下で入力してください。';
  return null;
};
