import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

export class AccountService {
  private apiClient: EnhancedApiClient;

  constructor(apiClient: EnhancedApiClient) {
    this.apiClient = apiClient;
  }

  async updateEmail(email: string): Promise<void> {
    await this.apiClient.post('/api/user', { email });
  }

  async updateName(name: string): Promise<void> {
    await this.apiClient.post('/api/user', { name });
  }

  async updatePassword(password: string): Promise<void> {
    await this.apiClient.post('/api/user', { password });
  }

  async checkNameAvailability(name: string): Promise<boolean> {
    try {
      const response = await this.apiClient.post<{ available: boolean }>(
        '/api/user/check-name',
        { name }
      );
      return response.available;
    } catch (error) {
      console.error('ユーザー名の可用性チェックに失敗しました', error);
      return false;
    }
  }
}
