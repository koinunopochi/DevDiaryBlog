import React from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { UserService } from '@/services/UserService';
import Profile from '@/views/components/blocks/profile/Profile';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

interface UsernamePageProps {
  apiClient?: EnhancedApiClient;
}

const UsernamePage: React.FC<UsernamePageProps> = ({ apiClient }) => {
  const navigate = useNavigate();
  const { username } = useParams<{ username: string }>();

  const handleEditProfile = () => {
    navigate('/settings/profile');
  };
  if (apiClient) {
    const userService = new UserService(apiClient);
    return (
      <Profile
        isEditable={true}
        onEditProfile={handleEditProfile}
        userData={() =>
          userService.getUserInfo({
            search_type: 'name',
            value: username || '',
          })
        }
      />
    );
  }
};

export default UsernamePage;
