import React from 'react';
import SettingsPageLayout from '@components/modules/settingsPageLayout/SettingsPageLayout';
import ProfileForm, {
  ProfileFormData,
} from '@components/blocks/profileForm/ProfileForm';
import { UserDetailsResponse } from '@/services/UserService';

interface ProfilePageProps {
  initialData: ProfileFormData | (() => Promise<UserDetailsResponse>);
  defaultProfileIcons: Array<string> | (() => Promise<string[]>);
  onSubmit: (data: ProfileFormData) => Promise<void>;
}

const ProfilePage: React.FC<ProfilePageProps> = ({
  initialData,
  defaultProfileIcons,
  onSubmit,
}) => {
  const handleSubmit = async(data: ProfileFormData) => {
    // ここで必要に応じてデータの処理や検証を行うことができます
    await onSubmit(data);
  };

  return (
    <SettingsPageLayout title="プロフィール設定" activeItemName="プロフィール">
      <ProfileForm
        initialData={initialData}
        defaultProfileIcons={defaultProfileIcons}
        onSubmit={handleSubmit}
      />
    </SettingsPageLayout>
  );
};

export default ProfilePage;
