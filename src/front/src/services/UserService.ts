import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

export interface UserDetailsResponse {
  id: string;
  profile: null | Profile;
  user: User;
}

export interface Profile {
  avatarUrl?: string;
  bio?: string;
  displayName?: string;
  socialLinks?: { [key: string]: string };
}

export interface User {
  createdAt: string;
  email: string;
  name: string;
  status: string;
  updatedAt: string;
}

export class UserService {
  private apiClient: EnhancedApiClient;

  constructor(apiClient: EnhancedApiClient) {
    this.apiClient = apiClient;
  }

  async getUserInfo(name:string): Promise<UserDetailsResponse> {
    try {
      const response = await this.apiClient.get<UserDetailsResponse>(
        `/api/user?search_type=name&value${name}`
      );
      return response;
    } catch (error) {
      console.error('ユーザー情報の取得に失敗しました', error);
      throw error;
    }
  }
}
