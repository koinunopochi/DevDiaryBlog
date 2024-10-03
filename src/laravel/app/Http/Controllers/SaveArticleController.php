<?php

namespace App\Http\Controllers;

use App\Application\UseCases\SaveArticleUseCase;
use App\Application\DataTransferObjects\SaveArticleDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveArticleController extends Controller
{
  private SaveArticleUseCase $saveArticleUseCase;

  public function __construct(SaveArticleUseCase $saveArticleUseCase)
  {
    $this->saveArticleUseCase = $saveArticleUseCase;
  }

  public function execute(Request $request): JsonResponse
  {
    try {
      $dto = new SaveArticleDTO($request);
      $this->saveArticleUseCase->execute($dto);

      return response()->json([
        'message' => '記事の保存に成功しました',
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => '記事の保存に失敗しました: ' . $e->getMessage()], 500);
    }
  }
}
