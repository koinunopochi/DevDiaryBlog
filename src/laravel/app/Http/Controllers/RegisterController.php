<?php

namespace App\Http\Controllers;

use App\Domain\ValueObjects\UserId;
use App\Models\User; // ユーザーモデルをインポート
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  public function register(Request $request): JsonResponse
  {
    try {
      Log::info('RegisterController register');
      // バリデーションルールを設定
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
      ]);

      // バリデーションエラーがあればエラーメッセージを返す
      if ($validator->fails()) {
        return new JsonResponse([
          'message' => 'Userの登録に失敗しました。',
          'errors' => $validator->errors()
        ], 422);
      }

      $userId = new UserId();

      Log::info('UserId', ['userId' => $userId->toDb()]);
      // ユーザーを作成
      $user = User::create([
        'id' => $userId->toDb(),
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      // ユーザ登録成功のレスポンスを返す
      return new JsonResponse([
        'message' => 'Userの登録が完了しました',
        'user' => [
          'id' => $userId->toString(),
          'name' => $user->name,
          'email' => $user->email,
        ]
      ], 201);
    } catch (Exception $e) {
      Log::error('RegisterController register error', ['error' => $e]);
      return new JsonResponse([
        'message' => 'Userの登録に失敗しました。',
        'errors' => $e->getMessage()
      ], 500);
    }
  }
}
