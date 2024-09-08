import { ApiClient, RequestOptions } from './ApiClient';

/**
 * 419でX-XSRF-TOKENの期限切れなどの場合に、再度アクセスをする機能をもつApiClient
 * X-XSRF-TOKENをメモリ上で保持し、クッキーから取得する
 * 3回連続で419エラーが発生した場合、エラーをスローする
 */
export class EnhancedApiClient extends ApiClient {
  private csrfTokenEndpoint: string;
  private xsrfToken: string | null = null;
  private consecutiveCSRFErrors: number = 0;
  private static MAX_CSRF_RETRIES: number = 3;

  constructor(baseUrl: string, csrfTokenEndpoint: string) {
    super(baseUrl, true);
    this.csrfTokenEndpoint = csrfTokenEndpoint;
  }

  private async refreshCsrfToken(): Promise<void> {
    try {
      const response = await fetch(`${this.baseUrl}${this.csrfTokenEndpoint}`, {
        method: 'GET',
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
        const [, value] = xsrfCookie.split('=');
        this.xsrfToken = decodeURIComponent(value);
      } else {
        console.warn(
          'XSRF-TOKENクッキーが見つかりませんでした。CSRFトークンが必要な操作は失敗する可能性があります。'
        );
        this.xsrfToken = '';
      }
    } catch (error) {
      console.error('CSRFトークンの取得に失敗しました', error);
      this.xsrfToken = '';
    }
  }

  private async retryWithNewToken<T>(
    endpoint: string,
    options: RequestOptions
  ): Promise<T> {
    if (this.consecutiveCSRFErrors >= EnhancedApiClient.MAX_CSRF_RETRIES) {
      this.consecutiveCSRFErrors = 0;
      throw new Error(
        'CSRFトークンの更新が繰り返し失敗しました。ログインし直してください。'
      );
    }

    await this.refreshCsrfToken();
    return this.request<T>(endpoint, options);
  }

  protected async request<T>(
    endpoint: string,
    options: RequestOptions
  ): Promise<T> {
    if (!this.xsrfToken) {
      await this.refreshCsrfToken();
    }

    const headers = {
      ...options.headers,
      'X-XSRF-TOKEN': this.xsrfToken || '',
    };

    try {
      const result = await super.request<T>(endpoint, { ...options, headers });
      this.consecutiveCSRFErrors = 0; // 成功したらカウンタをリセット
      return result;
    } catch (error) {
      if (error instanceof Error && error.message.includes('status: 419')) {
        console.log(
          `CSRF token mismatch detected. Retrying... (Attempt ${this.consecutiveCSRFErrors + 1})`
        );
        this.consecutiveCSRFErrors++;
        return this.retryWithNewToken<T>(endpoint, options);
      }
      this.consecutiveCSRFErrors = 0; // 419以外のエラーの場合もカウンタをリセット
      throw error;
    }
  }

  async get<T>(endpoint: string, params?: Record<string, string>): Promise<T> {
    const url = params
      ? `${endpoint}?${new URLSearchParams(params)}`
      : endpoint;
    return this.request<T>(url, { method: 'GET' });
  }

  async post<T>(endpoint: string, body: any): Promise<T> {
    return this.request<T>(endpoint, { method: 'POST', body });
  }

  async put<T>(endpoint: string, body: any): Promise<T> {
    return this.request<T>(endpoint, { method: 'PUT', body });
  }

  async delete<T>(endpoint: string): Promise<T> {
    return this.request<T>(endpoint, { method: 'DELETE' });
  }
}
