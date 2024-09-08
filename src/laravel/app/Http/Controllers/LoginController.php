<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function authenticate(Request $request): JsonResponse
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      return new JsonResponse([
        'message' => 'ログインしました。',
        'user' => Auth::user(),
      ]);
    }

    return new JsonResponse([
      'message' => 'ログインに失敗しました。',
    ], 401);
  }
}
