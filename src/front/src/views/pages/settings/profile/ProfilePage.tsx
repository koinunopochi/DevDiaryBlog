import React from 'react';
import SettingsPageLayout from '@components/modules/settingsPageLayout/SettingsPageLayout';
import ProfileForm, { ProfileFormData } from '@components/blocks/profileForm/ProfileForm';

interface ProfilePageProps {
  initialData?: ProfileFormData;
  defaultProfileIcons: Array<string>;
  onSubmit: (data: ProfileFormData) => void;
}

const ProfilePage: React.FC<ProfilePageProps> = ({
  initialData,
  defaultProfileIcons,
  onSubmit,
}) => {
  const handleSubmit = (data: ProfileFormData) => {
    // ここで必要に応じてデータの処理や検証を行うことができます
    onSubmit(data);
  };

  return (
    <SettingsPageLayout title="プロフィール設定">
      <ProfileForm
        initialData={initialData}
        defaultProfileIcons={defaultProfileIcons}
        onSubmit={handleSubmit}
      />
    </SettingsPageLayout>
  );
};

export default ProfilePage;
