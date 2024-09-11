<?php

namespace App\Http\Controllers;

use App\Application\UseCases\FindUserByIdUseCase;
use App\Domain\ValueObjects\UserId;
use Database\Seeders\SystemUserSeeder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  private FindUserByIdUseCase $findUserByIdUseCase;
  public function __construct(FindUserByIdUseCase $findUserByIdUseCase)
  {
    $this->findUserByIdUseCase = $findUserByIdUseCase;
  }
  public function authenticate(Request $request): JsonResponse
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    if (SystemUserSeeder::SYSTEM_USER_EMAIL === $credentials['email']) {
      return new JsonResponse([
        'message' => 'ログインに失敗しました。',
      ], 401);
    }

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      $user = $this->findUserByIdUseCase->execute(new UserId(Auth::id()));
      $removedIdArray = collect($user->toArray())->except('id')->toArray();
      return new JsonResponse([
        'message' => 'ログインしました。',
        'user' => $removedIdArray,
        'id' => Auth::id()
      ]);
    }

    return new JsonResponse([
      'message' => 'ログインに失敗しました。',
    ], 401);
  }
}
