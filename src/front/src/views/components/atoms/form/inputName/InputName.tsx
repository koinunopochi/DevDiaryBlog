import React, {
  useState,
  useCallback,
  useEffect,
  useMemo,
  useRef,
} from 'react';
import { debounce } from 'lodash';
import { Loader2 } from 'lucide-react';
import Input from '@components/atoms/form/input/Input';
import RequirementItem from '@components/atoms/requirement/RequirementItem';

interface InputNameProps
  extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
  initialValue?: string;
  onInputChange?: (value: string, isValid: boolean) => void;
  checkNameAvailability: (name: string) => Promise<boolean>;
}

const InputName: React.FC<InputNameProps> = ({
  initialValue = '',
  onInputChange,
  checkNameAvailability,
}) => {
  const [inputValue, setInputValue] = useState(initialValue);
  const [isChecking, setIsChecking] = useState(false);
  const [hasInteracted, setHasInteracted] = useState(false);
  const [requirements, setRequirements] = useState({
    length: false,
    characters: false,
    available: null as boolean | null,
  });

  const previousValueRef = useRef(initialValue);
  const isInitialMount = useRef(true);

  const validateName = useCallback(
    (value: string): string | null => {
      if (
        value.length < 3 ||
        value.length > 20 ||
        !/^[a-zA-Z0-9_]+$/.test(value) ||
        requirements.available === false
      ) {
        return 'ユーザー名は全ての要件を満たす必要があります';
      }
      return null;
    },
    [requirements.available]
  );

  const updateRequirements = useCallback((value: string) => {
    setRequirements((prevReq) => ({
      ...prevReq,
      length: value.length >= 3 && value.length <= 20,
      characters: /^[a-zA-Z0-9_]+$/.test(value),
    }));
  }, []);

  const checkAvailability = useCallback(
    async (name: string) => {
      if (
        name.length >= 3 &&
        name.length <= 20 &&
        /^[a-zA-Z0-9_]+$/.test(name)
      ) {
        setIsChecking(true);
        try {
          const available = await checkNameAvailability(name);
          console.log('API response:', available);
          setRequirements((prevReq) => ({
            ...prevReq,
            available,
          }));
        } catch (error) {
          console.error('Error checking availability:', error);
          setRequirements((prevReq) => ({ ...prevReq, available: null }));
        } finally {
          setIsChecking(false);
          // APIチェック後に現在の入力値を再確認
          const currentValue = previousValueRef.current;
          if (
            currentValue.length < 3 ||
            currentValue.length > 20 ||
            !/^[a-zA-Z0-9_]+$/.test(currentValue)
          ) {
            setRequirements((prevReq) => ({ ...prevReq, available: null }));
          }
        }
      } else {
        setRequirements((prevReq) => ({ ...prevReq, available: null }));
      }
    },
    [checkNameAvailability]
  );

  const debouncedCheckAvailability = useMemo(
    () =>
      debounce((value: string) => {
        if (value.length >= 3 && /^[a-zA-Z0-9_]+$/.test(value)) {
          checkAvailability(value);
        }
      }, 500),
    [checkAvailability]
  );

  useEffect(() => {
    return () => {
      debouncedCheckAvailability.cancel();
    };
  }, [debouncedCheckAvailability]);

  const handleInputChange = useCallback(
    (value: string, isValid: boolean) => {
      if (value !== previousValueRef.current) {
        setInputValue(value);
        updateRequirements(value);
        if (value.length >= 3 && /^[a-zA-Z0-9_]+$/.test(value)) {
          debouncedCheckAvailability(value);
        } else {
          setRequirements((prevReq) => ({ ...prevReq, available: null }));
        }
        if (onInputChange) {
          onInputChange(value, isValid);
        }
        if (!hasInteracted && value !== '') {
          setHasInteracted(true);
        }
        previousValueRef.current = value;
      }
    },
    [
      debouncedCheckAvailability,
      onInputChange,
      hasInteracted,
      updateRequirements,
    ]
  );

  useEffect(() => {
    if (isInitialMount.current) {
      if (initialValue !== '') {
        updateRequirements(initialValue);
        setHasInteracted(true);
        if (initialValue.length >= 3 && /^[a-zA-Z0-9_]+$/.test(initialValue)) {
          checkAvailability(initialValue);
        }
      }
      isInitialMount.current = false;
    }
  }, [initialValue, checkAvailability, updateRequirements]);

  return (
    <div className="relative">
      <Input
        label="ユーザー名"
        value={inputValue}
        onChange={handleInputChange}
        validate={validateName}
        required
        placeholder="user_name"
      />
      <div className="absolute top-0 right-0 mt-11 mr-2">
        {isChecking && (
          <Loader2 className="h-5 w-5 text-blue-500 animate-spin" />
        )}
      </div>
      <ul
        className={`
          text-xs mt-2 space-y-1
          transition-all duration-300 ease-in-out
          ${!hasInteracted ? 'opacity-0 max-h-0 overflow-hidden' : 'opacity-100 max-h-96'}
        `}
      >
        <RequirementItem met={requirements.length} isInitial={!hasInteracted}>
          3文字以上20文字以下
        </RequirementItem>
        <RequirementItem
          met={requirements.characters}
          isInitial={!hasInteracted}
        >
          半角英数字とアンダースコアのみ
        </RequirementItem>
        <RequirementItem
          met={requirements.available === true}
          isInitial={!hasInteracted || requirements.available === null}
        >
          {isChecking ? '利用可能性を確認中...' : '利用可能な名前'}
        </RequirementItem>
      </ul>
    </div>
  );
};

export default InputName;
