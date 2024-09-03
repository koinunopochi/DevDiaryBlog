<?php

namespace App\Http\Controllers;

use App\Application\Services\GetUserDetailsService;
use App\Domain\ValueObjects\UserId;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GetUserDetailsController extends Controller
{
  private GetUserDetailsService $getUserDetailsService;

  public function __construct(GetUserDetailsService $getUserDetailsService)
  {
    $this->getUserDetailsService = $getUserDetailsService;
  }

  public function execute(Request $request, string $userId): JsonResponse
  {
    try {
      $userDetails = $this->getUserDetailsService->execute(new UserId($userId));

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
