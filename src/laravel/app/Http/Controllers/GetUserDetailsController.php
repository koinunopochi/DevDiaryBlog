<?php

namespace App\Http\Controllers;

use App\Application\Services\GetUserDetailsByNameService;
use App\Application\Services\GetUserDetailsByUserIdService;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
    try {
      $searchType = $request->query('search_type');
      $searchValue = $request->query('value');

      if ($searchType === 'id') {
        $userDetails = $this->getUserDetailsByUserIdService->execute(new UserId($searchValue));
      } elseif ($searchType === 'name') {
        $userDetails = $this->getUserDetailsByNameService->execute(new Username($searchValue));
      } else {
        return new JsonResponse(['error' => 'Invalid search type'], 400);
      }

      if (!$userDetails) {
        return new JsonResponse(['error' => 'User not found'], 404);
      }

      return new JsonResponse($userDetails->toArray());
    } catch (Exception $e) {
      if ($e instanceof \InvalidArgumentException) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
      }

      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }
}
