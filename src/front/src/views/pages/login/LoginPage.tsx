import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import AuthService from '@/services/AuthService';
import InputEmail from '@components/atoms/form/inputEmail/InputEmail';
import InputPassword from '@components/atoms/form/inputPassword/InputPassword';
import SubmitButton from '@/views/components/atoms/submitButton/SubmitButton';
import { Send } from 'lucide-react';

interface LoginPageProps {
  authService: AuthService;
}

const LoginPage: React.FC<LoginPageProps> = ({ authService }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isEmailValid, setIsEmailValid] = useState(false);
  const [isPasswordValid, setIsPasswordValid] = useState(false);
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [errorMessage, setErrorMessage] = useState('');
  const navigate = useNavigate();

  const handleEmailChange = (value: string, isValid: boolean) => {
    setEmail(value);
    setIsEmailValid(isValid);
  };

  const handlePasswordChange = (value: string, isValid: boolean) => {
    setPassword(value);
    setIsPasswordValid(isValid);
  };

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (isEmailValid && isPasswordValid) {
      try {
        setIsLoading(true);
        await authService.login(email, password);
        setIsLoading(false);
        navigate('/');
      } catch (error) {
        setIsLoading(false);
        setErrorMessage(
          error instanceof Error
            ? error.message
            : '予期しないエラーが発生しました。'
        );
      }
    } else {
      setErrorMessage('メールアドレスまたはパスワードが無効です。');
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen py-2 px-4 sm:px-6 lg:px-8">
      <div className="p-4 sm:p-6 md:p-8 rounded-lg border border-gray-300 dark:border-gray-700 w-full max-w-md bg-white dark:bg-gray-800">
        <h2 className="text-xl sm:text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200 text-center">
          ログイン
        </h2>
        {errorMessage && (
          <p className="text-red-500 dark:text-red-400 mb-4 text-center">
            {errorMessage}
          </p>
        )}
        <form onSubmit={handleSubmit} className="space-y-4 sm:space-y-6">
          <div>
            <InputEmail value={email} onChange={handleEmailChange} />
          </div>
          <div>
            <InputPassword value={password} onChange={handlePasswordChange} />
          </div>
          <div className="flex items-center justify-center">
            <div className="flex justify-end pt-2 sm:pt-4">
              <SubmitButton
                icon={Send}
                disabled={!isEmailValid && !isPasswordValid}
                isLoading={isLoading}
              >
                Login
              </SubmitButton>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
};

export default LoginPage;
