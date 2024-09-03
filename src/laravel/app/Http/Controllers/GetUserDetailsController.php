<?php

namespace App\Http\Controllers;

use App\Application\Services\GetUserDetailsByUserIdService;
use App\Domain\ValueObjects\UserId;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GetUserDetailsController extends Controller
{
  private GetUserDetailsByUserIdService $getUserDetailsByUserIdService;

  public function __construct(GetUserDetailsByUserIdService $getUserDetailsByUserIdService)

  {
    $this->getUserDetailsByUserIdService = $getUserDetailsByUserIdService;
  }

  public function execute(Request $request, string $userId): JsonResponse
  {
    try {
      $userDetails = $this->getUserDetailsByUserIdService->execute(new UserId($userId));

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
