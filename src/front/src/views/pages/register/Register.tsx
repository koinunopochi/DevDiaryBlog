import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import AuthService from '../../../services/AuthService';
import InputEmail from '@components/atoms/form/inputEmail/InputEmail';
import InputPassword from '@components/atoms/form/inputPassword/InputPassword';

const RegisterPage: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isEmailValid, setIsEmailValid] = useState(false);
  const [isPasswordValid, setIsPasswordValid] = useState(false);
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
        await AuthService.register(email, password);
        navigate('/');
      } catch (error: any) {
        setErrorMessage(error.message);
      }
    } else {
      setErrorMessage('メールアドレスまたはパスワードが無効です。');
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen py-2">
      <div className="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 className="text-2xl font-bold mb-4">登録</h2>
        {errorMessage && <p className="text-red-500 mb-4">{errorMessage}</p>}
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <InputEmail
              value={email}
              onChange={handleEmailChange}
            />
          </div>
          <div className="mb-6">
            <InputPassword
              value={password}
              onChange={handlePasswordChange}
            />
          </div>
          <div className="flex items-center justify-between">
            <button
              type="submit"
              className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              disabled={!isEmailValid || !isPasswordValid}
            >
              登録
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default RegisterPage;
