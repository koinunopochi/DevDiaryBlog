<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetAllTagNamesUseCase;
use Illuminate\Http\JsonResponse;

class GetAllTagNamesController extends Controller
{
  private GetAllTagNamesUseCase $getAllTagNamesUseCase;

  public function __construct(GetAllTagNamesUseCase $getAllTagNamesUseCase)
  {
    $this->getAllTagNamesUseCase = $getAllTagNamesUseCase;
  }

  public function execute(): JsonResponse
  {
    try {
      $result = $this->getAllTagNamesUseCase->execute();
      return response()->json(['tag_names' => $result->toArray()]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
