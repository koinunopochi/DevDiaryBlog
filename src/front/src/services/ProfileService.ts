import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { ProfileFormData } from '@/views/components/blocks/profileForm/ProfileForm';

interface DefaultIconsResponse {
  icons: {
    bucketPath: string;
    files: string[];
  };
}

export class ProfileService {
  private apiClient: EnhancedApiClient;

  constructor(apiClient: EnhancedApiClient) {
    this.apiClient = apiClient;
  }

  async saveProfile(profileData: ProfileFormData): Promise<void> {
    try {
      await this.apiClient.post('/api/profile', profileData);
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
}
