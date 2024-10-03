<?php

namespace App\Http\Controllers;

use App\Application\UseCases\CreateDraftArticleUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateDraftArticleController extends Controller
{
  private CreateDraftArticleUseCase $createDraftArticleUseCase;

  public function __construct(CreateDraftArticleUseCase $createDraftArticleUseCase)
  {
    $this->createDraftArticleUseCase = $createDraftArticleUseCase;
  }

  public function execute(): JsonResponse
  {
    try {
      $draftArticle = $this->createDraftArticleUseCase->execute();

      return response()->json([
        'message' => '下書き記事の作成に成功しました',
        'data' => [
          'articleId' => $draftArticle->getId()->toString(),
          'status' => $draftArticle->getStatus()->toString(),
          'createdAt' => $draftArticle->getCreatedAt()->toString()
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => '下書き記事の作成に失敗しました: ' . $e->getMessage()], 500);
    }
  }
}
