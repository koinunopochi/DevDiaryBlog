<?php

namespace App\Http\Controllers;

use App\Application\DataTransferObjects\SaveProfileDTO;
use App\Application\UseCases\SaveProfileUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SaveProfileController extends Controller
{
  private SaveProfileUseCase $saveProfileUseCase;

  public function __construct(SaveProfileUseCase $saveProfileUseCase)
  {
    $this->saveProfileUseCase = $saveProfileUseCase;
  }

  public function execute(Request $request): JsonResponse
  {
    try {
      $userId = Auth::user()->id;

      $this->saveProfileUseCase->execute(new SaveProfileDTO($request, $userId));

      return new JsonResponse(['message' => 'Profile saved']);
    } catch (\Exception $e) {

      if ($e instanceof \InvalidArgumentException) {
        return new JsonResponse(['error' => $e->getMessage()], 400);
      }
      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }
}
