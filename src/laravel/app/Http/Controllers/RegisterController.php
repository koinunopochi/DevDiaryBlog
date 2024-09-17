<?php

namespace App\Http\Controllers;

use App\Application\UseCases\FindUserByIdUseCase;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Models\EloquentProfile;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  private FindUserByIdUseCase $findUserByIdUseCase;

  public function __construct(FindUserByIdUseCase $findUserByIdUseCase)
  {
    $this->findUserByIdUseCase = $findUserByIdUseCase;
  }

  public function register(Request $request): JsonResponse
  {
    try {
      Log::info('RegisterController register');

      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
      ]);

      if ($validator->fails()) {
        return new JsonResponse([
          'message' => 'Userの登録に失敗しました。',
          'errors' => $validator->errors()
        ], 422);
      }

      $userId = new UserId();
      Log::info('UserId', ['userId' => $userId->toString()]);

      DB::beginTransaction();

      try {
        $user = User::factory()->create([
          'id' => $userId->toString(),
          'name' => (new Username($request->name))->toString(),
          'email' => (new Email($request->email))->toString(),
          'password' => (new Password($request->password))->toString(),
        ]);

        EloquentProfile::factory()->create([
          'user_id' => $userId->toString(),
          'display_name' => (new Username($request->name))->toString(),
          'bio' => (new UserBio(''))->toString(),
          'avatar_url' => (new Url(Config::get('services.s3.default_icon')))->toString(),
          'social_links' => json_encode([]),
        ]);

        DB::commit();
      } catch (Exception $e) {
        DB::rollBack();
        throw $e;
      }

      Auth::login($user);

      $user = $this->findUserByIdUseCase->execute($userId);
      $removedIdArray = collect($user->toArray())->except('id')->toArray();

      return new JsonResponse([
        'message' => 'Userの登録が完了しました',
        'user' => $removedIdArray,
        'id' => $userId->toString(),
      ], 200);
    } catch (Exception $e) {
      Log::error('RegisterController register error', ['error' => $e]);
      return new JsonResponse([
        'message' => 'Userの登録に失敗しました。',
        'errors' => $e->getMessage()
      ], 500);
    }
  }
}
