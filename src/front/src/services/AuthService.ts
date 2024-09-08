import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';

export interface LoginResponse {
  message: string;
  user: {
    id: string;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    status: string;
  };
}

export default class AuthService {
  private apiClient: EnhancedApiClient;

  constructor(apiClient: EnhancedApiClient) {
    this.apiClient = apiClient;
  }

  async login(email: string, password: string): Promise<LoginResponse> {
    try {
      const data = await this.apiClient.post<LoginResponse>('/api/login', {
        email,
        password,
      });

      // ローカルストレージに保存
      localStorage.setItem('user', JSON.stringify(data.user));

      return data;
    } catch (error) {
      console.error('ログインに失敗しました', error);
      throw error;
    }
  }

  async register(email: string, password: string): Promise<any> {
    try {
      const data = await this.apiClient.post<any>('/api/register', {
        email,
        password,
        name: this.randomUserNameGenerate(),
      });
      // 登録成功時の処理
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
    const userStr = localStorage.getItem('user');
    if (userStr) {
      const user = JSON.parse(userStr);
      return user.id;
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
