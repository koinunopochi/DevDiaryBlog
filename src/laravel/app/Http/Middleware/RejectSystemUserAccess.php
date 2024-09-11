<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Database\Seeders\SystemUserSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RejectSystemUserAccess
{
  public function handle(Request $request, Closure $next)
  {
    Log::info("RejectSystemUserAccess middleware called");

    // 認証済みかどうかを確認
    if (Auth::check()) {
      $user = Auth::user();
      Log::debug("Authenticated user: " . json_encode($user));

      if ($user->id === SystemUserSeeder::SYSTEM_USER_UUID) {
        // システムユーザーのアクセス試行をログに記録
        Log::warning('System user access attempt detected', [
          'ip' => $request->ip(),
          'user_agent' => $request->userAgent(),
          'url' => $request->fullUrl(),
        ]);

        // ユーザーをログアウト
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // アクセス拒否のレスポンスを返す
        return response()->json([
          'message' => 'Access denied. System user is not allowed to access this resource.',
        ], 403);
      }
    } else {
      Log::debug("No authenticated user");
    }

    return $next($request);
  }
}
