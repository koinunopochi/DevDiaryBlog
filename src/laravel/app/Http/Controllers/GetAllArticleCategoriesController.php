<?php

namespace App\Http\Controllers;

use App\Application\Services\GetAllArticleCategoriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GetAllArticleCategoriesController extends Controller
{
  private GetAllArticleCategoriesService $getAllArticleCategoriesService;

  public function __construct(GetAllArticleCategoriesService $getAllArticleCategoriesService)
  {
    $this->getAllArticleCategoriesService = $getAllArticleCategoriesService;
  }

  public function execute(): JsonResponse
  {
    try {
      Log::debug('class : GetAllArticleCategoriesController - method : execute - $message : start');
      $result = $this->getAllArticleCategoriesService->execute();
      return response()->json(['categories' => $result]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
