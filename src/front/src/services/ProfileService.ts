import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { ProfileFormData } from '@/views/components/blocks/profileForm/ProfileForm';
import { UserService } from '@/services/UserService';
import AuthService from '@/services/AuthService';

interface DefaultIconsResponse {
  icons: {
    bucketPath: string;
    files: string[];
  };
}

export class ProfileService {
  private apiClient: EnhancedApiClient;
  private userService: UserService;
  private authService: AuthService;

  constructor(
    apiClient: EnhancedApiClient,
    userService: UserService,
    authService: AuthService
  ) {
    this.apiClient = apiClient;
    this.userService = userService;
    this.authService = authService;
  }

  async saveProfile(profileData: ProfileFormData): Promise<void> {
    try {
      await this.apiClient.post('/api/profile', profileData);
      await this.updateUserDetails();
    } catch (error) {
      console.error('プロフィールの保存に失敗しました', error);
      throw error;
    }
  }

  async getDefaultProfileIcons(): Promise<string[]> {
    try {
      const response = await this.apiClient.get<DefaultIconsResponse>(
        '/api/profile-icons/defaults'
      );
      const { bucketPath, files } = response.icons;
      return files.map((file) => `${bucketPath}/${file}`);
    } catch (error) {
      console.error(
        'デフォルトプロフィールアイコンの取得に失敗しました',
        error
      );
      return [];
    }
  }

  private async updateUserDetails(): Promise<void> {
    await this.userService.getUserInfo({
      search_type: 'id',
      value: this.authService.getUserId(),
    });
  }
}
