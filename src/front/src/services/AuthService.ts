class AuthService {
  private SERVER_URL = 'http://localhost:8080';
  private API_URL = `${this.SERVER_URL}/api`;

  async getCsrfToken(): Promise<string> {
    try {
      const response = await fetch(`${this.SERVER_URL}/sanctum/csrf-cookie`, {
        credentials: 'include',
        headers: {
          Accept: 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error('CSRFトークンの取得に失敗しました');
      }

      const cookies = document.cookie.split(';');
      const xsrfCookie = cookies.find((cookie) =>
        cookie.trim().startsWith('XSRF-TOKEN=')
      );

      if (xsrfCookie) {
        const [, value] = xsrfCookie.split('='); // XSRF-TOKEN=の部分を削除
        return decodeURIComponent(value);
      } else {
        console.warn(
          'XSRF-TOKENクッキーが見つかりませんでした。CSRFトークンが必要な操作は失敗する可能性があります。'
        );
        return '';
      }
    } catch (error) {
      console.error('CSRFトークンの取得に失敗しました', error);
      return '';
    }
  }

  async login(email: string, password: string): Promise<any | Error> {
    try {
      const csrfToken = await this.getCsrfToken();
      const response = await fetch(`${this.API_URL}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-XSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ email, password }),
        credentials: 'include',
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
      const csrfToken = await this.getCsrfToken();
      const response = await fetch(`${this.API_URL}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-XSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
          email,
          password,
          name: 'default-user-' + Math.random(),
        }),
        credentials: 'include',
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

  async logout(): Promise<void> {
    const csrfToken = await this.getCsrfToken();
    const response = await fetch(`${this.API_URL}/logout`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': csrfToken,
      },
      credentials: 'include',
    });

    if (!response.ok) {
      throw new Error('ログアウトに失敗しました');
    }

    // ログアウト処理（トークン削除など）
    localStorage.removeItem('token');
  }
}

export default new AuthService();
