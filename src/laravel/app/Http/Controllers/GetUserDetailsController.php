<?php

namespace App\Http\Controllers;

use App\Application\Services\GetUserDetailsByNameService;
use App\Application\Services\GetUserDetailsByUserIdService;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use Database\Seeders\SystemUserSeeder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetUserDetailsController extends Controller
{
  private GetUserDetailsByUserIdService $getUserDetailsByUserIdService;
  private GetUserDetailsByNameService $getUserDetailsByNameService;

  public function __construct(
    GetUserDetailsByUserIdService $getUserDetailsByUserIdService,
    GetUserDetailsByNameService $getUserDetailsByNameService
  ) {
    $this->getUserDetailsByUserIdService = $getUserDetailsByUserIdService;
    $this->getUserDetailsByNameService = $getUserDetailsByNameService;
  }

  public function execute(Request $request): JsonResponse
  {
    // note: SystemUserの場合は、存在しないとしてアクセスさせない
    try {
      $searchType = $request->query('search_type');
      $searchValue = $request->query('value');

      if ($searchType === 'id') {
        if (SystemUserSeeder::SYSTEM_USER_UUID === $searchValue) {
          return new JsonResponse(['error' => 'User not found'], 404);
        }

        $userDetails = $this->getUserDetailsByUserIdService->execute(new UserId($searchValue));
      } elseif ($searchType === 'name') {
        if (SystemUserSeeder::SYSTEM_USER_NAME === $searchValue) {
          return new JsonResponse(['error' => 'User not found'], 404);
        }

        $userDetails = $this->getUserDetailsByNameService->execute(new Username($searchValue));
      } else {
        return new JsonResponse(['error' => 'Invalid search type'], 400);
      }

      if (!$userDetails) {
        return new JsonResponse(['error' => 'User not found'], 404);
      }

      $canUpdate = false;
      try {
        // note: 未認証・同じユーザー出なない場合はfalse
        // TODO: 今後ポリシーで切るように変更する

        $canUpdate = $userDetails->canUpdate(new UserId(Auth::id()));
      } catch (Exception $e) {
        $canUpdate = false;
      }

      return new JsonResponse(array_merge(
        $userDetails->toArray(),
        [
          'auth' => [
            'canUpdate' => $canUpdate,
          ]
        ]
      ));
    } catch (Exception $e) {
      if ($e instanceof \InvalidArgumentException) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
      }

      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }
}
