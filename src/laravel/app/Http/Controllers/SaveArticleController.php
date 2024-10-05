<?php

namespace App\Http\Controllers;

use App\Application\UseCases\SaveArticleUseCase;
use App\Application\DataTransferObjects\SaveArticleDTO;
use App\Application\UseCases\GetOrCreateTagUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveArticleController extends Controller
{
  private SaveArticleUseCase $saveArticleUseCase;
  private GetOrCreateTagUseCase $getOrCreateTagUseCase;

  public function __construct(SaveArticleUseCase $saveArticleUseCase, GetOrCreateTagUseCase $getOrCreateTagUseCase)
  {
    $this->saveArticleUseCase = $saveArticleUseCase;
    $this->getOrCreateTagUseCase = $getOrCreateTagUseCase;
  }

  public function execute(Request $request): JsonResponse
  {
    try {
      $tagNames = $request->input('tags');
      $tagIds = [];
      foreach ($tagNames as $key => $tagName) {
        $tag = $this->getOrCreateTagUseCase->execute(new \App\Domain\ValueObjects\TagName($tagName));
        $tagIds[] = $tag->getId()->toString();
      }
      $request->merge(['tags' => $tagIds]);
      $dto = new SaveArticleDTO($request);
      $this->saveArticleUseCase->execute($dto);

      return response()->json([
        'message' => '記事の保存に成功しました',
        'article'=>[
          'id'=>$dto->toArticle()->getId()->toString()
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => '記事の保存に失敗しました: ' . $e->getMessage()], 500);
    }
  }
}
