import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { ProfileFormData } from '@/views/components/blocks/profileForm/ProfileForm';

export interface UserDetailsResponse {
  id: string;
  profile: null | Profile;
  user: User;
  auth:Auth;
}

export interface Auth {
  canUpdate: boolean;
}

export interface Profile {
  avatarUrl: string;
  bio: string;
  displayName: string;
  socialLinks: { [key: string]: string };
}

export interface User {
  createdAt: string;
  email: string;
  name: string;
  status: string;
  updatedAt: string;
}

type UserSearchByName = {
  search_type: 'name';
  value: string;
};

type UserSearchById = {
  search_type: 'id';
  value: string;
};

type UserSearchParams = UserSearchByName | UserSearchById;

export class UserService {
  private apiClient: EnhancedApiClient;

  constructor(apiClient: EnhancedApiClient) {
    this.apiClient = apiClient;
  }

  async getUserInfo(params: UserSearchParams): Promise<UserDetailsResponse> {
    try {
      const queryString = `search_type=${params.search_type}&value=${params.value}`;
      const response = await this.apiClient.get<UserDetailsResponse>(
        `/api/user?${queryString}`
      );

      return response;
    } catch (error) {
      console.error('ユーザー情報の取得に失敗しました', error);
      throw error;
    }
  }

  getProfile(): ProfileFormData {
    const profileStr = localStorage.getItem('profile');
    if (profileStr) {
      return JSON.parse(profileStr);
    }
    return { displayName: '', avatarUrl: '', bio: '', socialLinks: {} };
  }
}
