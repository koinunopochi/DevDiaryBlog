<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array<int, string>
   */
  protected $except = [
    //
  ];

  public function handle($request, Closure $next)
  {
    try {
      Log::info('VerifyCsrfToken middleware reached.'); // ログを追加

      return parent::handle($request, $next);
    } catch (\Exception $e) {
      Log::error('CSRF token mismatch.', ['exception' => $e]);
      throw $e;
    }
  }
}
