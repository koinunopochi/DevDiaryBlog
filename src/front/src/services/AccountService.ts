import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { UserService } from '@/services/UserService';
import AuthService from '@/services/AuthService';

export class AccountService {
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

  async updateEmail(email: string): Promise<void> {
    await this.apiClient.post('/api/user', { email });
    await this.updateUserDetails();
  }

  async updateName(name: string): Promise<void> {
    await this.apiClient.post('/api/user', { name });
    await this.updateUserDetails();
  }

  async updatePassword(password: string): Promise<void> {
    await this.apiClient.post('/api/user', { password });
    await this.updateUserDetails();
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

  private async updateUserDetails(): Promise<void> {
    await this.userService.getUserInfo({
      search_type: 'id',
      value: this.authService.getUserId(),
    });
  }
}
