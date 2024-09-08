import React from 'react';
import SettingsPageLayout from '@components/modules/settingsPageLayout/SettingsPageLayout';
import UserForm from '@components/blocks/userForm/UserForm';

interface AccountPageProps {
  initialEmail?: string;
  initialName?: string;
  onEmailSubmit: (email: string) => void;
  onPasswordSubmit: (password: string) => void;
  onNameSubmit: (name: string) => void;
  checkNameAvailability: (name: string) => Promise<boolean>;
}

const AccountPage: React.FC<AccountPageProps> = ({
  initialEmail,
  initialName,
  onEmailSubmit,
  onPasswordSubmit,
  onNameSubmit,
  checkNameAvailability,
}) => {
  return (
    <SettingsPageLayout title="アカウント設定" activeItemName="アカウント">
      <UserForm
        initialEmail={initialEmail}
        initialName={initialName}
        onEmailSubmit={onEmailSubmit}
        onPasswordSubmit={onPasswordSubmit}
        onNameSubmit={onNameSubmit}
        checkNameAvailability={checkNameAvailability}
      />
    </SettingsPageLayout>
  );
};

export default AccountPage;
