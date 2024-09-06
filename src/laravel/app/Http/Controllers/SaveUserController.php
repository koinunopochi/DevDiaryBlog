<?php

namespace App\Http\Controllers;

use App\Application\DataTransferObjects\SaveUserDTO;
use App\Application\UseCases\SaveUserUseCase;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaveUserController extends Controller
{
  private SaveUserUseCase $saveUserUseCase;

  public function __construct(SaveUserUseCase $saveUserUseCase)
  {
    $this->saveUserUseCase = $saveUserUseCase;
  }

  public function execute(Request $request): JsonResponse
  {
    $user = Auth::user();
    $userId = new UserId($user->id);

    $name = $request->input("name");
    $email = $request->input("email");
    $password = $request->input("password");

    if ($name === null && $email === null && $password === null) {
      return response()->json([
        'error' => 'name, email, passwordのいずれか一つ以上の値は存在する必要があります。'
      ], 400);
    }

    // note : 値オブジェクトを作成、null の場合は null を設定
    $nameValueObject = $name ? new Username($name) : null;
    $emailValueObject = $email ? new Email($email) : null;
    $passwordValueObject = $password ? new Password($password) : null;

    Log::debug(
      'class : SaveUserController - method : execute - $nameValueObject : ' . $nameValueObject->toString()
    );
    Log::debug(
      'class : SaveUserController - method : execute - $emailValueObject : ' . $emailValueObject->toString()
    );
    Log::debug(
      'class : SaveUserController - method : execute - $passwordValueObject : ' . $passwordValueObject->toString()
    );

    $dto = new SaveUserDTO(
      $nameValueObject,
      $emailValueObject,
      $passwordValueObject
    );

    try {
      $this->saveUserUseCase->execute($dto, $userId);
      return response()->json(['message' => 'ユーザー情報の更新に成功しました'], 200);
    } catch (\Exception $e) {
      if ($e instanceof \InvalidArgumentException) {
        return response()->json(['error' => $e->getMessage()], 400);
      }
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
