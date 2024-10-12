import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import { User, UserService } from '@/services/UserService';

export interface LoginResponse {
  message: string;
  id: string;
  user: User;
}

export interface RegisterResponse {
  message: string;
  id: string;
  user: User;
}

export default class AuthService {
  private apiClient: EnhancedApiClient;
  private userService: UserService;

  constructor(apiClient: EnhancedApiClient, userService: UserService) {
    this.apiClient = apiClient;
    this.userService = userService;
  }

  async login(email: string, password: string): Promise<LoginResponse> {
    try {
      const data = await this.apiClient.post<LoginResponse>('/api/login', {
        email,
        password,
      });

      // ローカルストレージに保存
      localStorage.setItem('userId', data.id);
      localStorage.setItem('user', JSON.stringify(data.user));

      const response = await this.userService.getUserInfo({
        search_type: 'id',
        value: data.id,
      });

      localStorage.setItem('userId', response.id);
      localStorage.setItem('user', JSON.stringify(response.user));
      localStorage.setItem('profile', JSON.stringify(response.profile));

      return data;
    } catch (error) {
      console.error('ログインに失敗しました', error);
      throw error;
    }
  }

  async register(email: string, password: string): Promise<RegisterResponse> {
    try {
      const data = await this.apiClient.post<RegisterResponse>(
        '/api/register',
        {
          email,
          password,
          name: this.randomUserNameGenerate(),
        }
      );

      // ローカルストレージに保存
      localStorage.setItem('userId', data.id);
      localStorage.setItem('user', JSON.stringify(data.user));

      return data;
    } catch (error) {
      console.error('登録に失敗しました', error);
      throw error;
    }
  }

  async logout(): Promise<void> {
    try {
      await this.apiClient.post<void>('/api/logout', {});
      localStorage.clear();
    } catch (error) {
      console.error('ログアウトに失敗しました', error);
      throw error;
    }
  }

  private randomUserNameGenerate(): string {
    const characters =
      'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
    return Array.from({ length: 20 }, () =>
      characters.charAt(Math.floor(Math.random() * characters.length))
    ).join('');
  }

  public getUserId(): string {
    const userStr = localStorage.getItem('userId');
    if (userStr) {
      return userStr;
    }
    return '';
  }

  public getUsername(): string {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      const user = JSON.parse(userStr);
      return user.name;
    }
    return '';
  }

  public getUserEmail(): string {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      const user = JSON.parse(userStr);
      return user.email;
    }
    return '';
  }
}

// 使用例
// const apiClient = new EnhancedApiClient('http://localhost:8080', '/sanctum/csrf-cookie');
// export const authService = new AuthService(apiClient);
