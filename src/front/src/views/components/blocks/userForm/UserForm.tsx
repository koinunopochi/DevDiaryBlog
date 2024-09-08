import React from 'react';
import EmailUpdateForm from '@components/blocks/emailUpdateForm/EmailUpdateForm';
import PasswordUpdateForm from '@components/blocks/passwordUpdateForm/PasswordUpdateForm';
import NameUpdateForm from '@components/blocks/nameUpdateForm/NameUpdateForm';

interface UserFormProps {
  initialEmail?: string;
  initialName?: string;
  onEmailSubmit: (email: string) => void;
  onPasswordSubmit: (password: string) => void;
  onNameSubmit: (name: string) => void;
  checkNameAvailability: (name: string) => Promise<boolean>;
}

const UserForm: React.FC<UserFormProps> = ({
  initialEmail,
  initialName,
  onEmailSubmit,
  onPasswordSubmit,
  onNameSubmit,
  checkNameAvailability,
}) => {
  return (
    <div className="max-w-3xl mx-auto p-1 sm:p-6 space-y-4 sm:space-y-6">
      <div className="border border-gray-200 rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">
        <section>
          <h2 className="text-lg sm:text-xl font-semibold mb-2">
            ユーザー名の変更
          </h2>
          <p className="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
            アカウントに利用される名前を設定します。
          </p>
          <NameUpdateForm
            initialName={initialName}
            onSubmit={onNameSubmit}
            checkNameAvailability={checkNameAvailability}
          />
        </section>
      </div>

      <div className="border border-gray-200 rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">
        <section>
          <h2 className="text-lg sm:text-xl font-semibold mb-2">
            メールアドレスの変更
          </h2>
          <p className="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
            アカウントに関連付けられるメールアドレスを更新します。
          </p>
          <EmailUpdateForm
            initialEmail={initialEmail}
            onSubmit={onEmailSubmit}
          />
        </section>
      </div>

      <div className="border border-gray-200 rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">
        <section>
          <h2 className="text-lg sm:text-xl font-semibold mb-2">
            パスワードの変更
          </h2>
          <p className="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
            アカウントのセキュリティを保護するために、強力なパスワードを設定してください。
          </p>
          <PasswordUpdateForm onSubmit={onPasswordSubmit} />
        </section>
      </div>
    </div>
  );
};

export default UserForm;
