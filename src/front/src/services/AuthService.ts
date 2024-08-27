class AuthService {
  private API_BASE_URL = 'https://your-api-base-url.com'; // APIのベースURLを設定

  async login(email: string, password: string): Promise<any | Error> {
    try {
      const response = await fetch(`${this.API_BASE_URL}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      if (!response.ok) {
        throw new Error('ログインに失敗しました');
      }

      const data = await response.json();
      // ログイン成功時の処理（トークン保存など）
      localStorage.setItem('token', data.token);
      return data;
    } catch (error) {
      // エラー処理
      console.error('ログインに失敗しました', error);
      throw error;
    }
  }

  async register(email: string, password: string): Promise<any | Error> {
    try {
      const response = await fetch(`${this.API_BASE_URL}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      if (!response.ok) {
        throw new Error('登録に失敗しました');
      }

      const data = await response.json();
      // 登録成功時の処理
      return data;
    } catch (error) {
      // エラー処理
      console.error('登録に失敗しました', error);
      throw error;
    }
  }

  logout(): void {
    // ログアウト処理（トークン削除など）
    localStorage.removeItem('token');
  }
}

export default new AuthService();
