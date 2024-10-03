<?php

namespace App\Http\Controllers;

use App\Application\UseCases\FindArticleByIdUseCase;
use App\Domain\ValueObjects\ArticleId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FindArticleByIdController extends Controller
{
  private FindArticleByIdUseCase $findArticleByIdUseCase;

  public function __construct(FindArticleByIdUseCase $findArticleByIdUseCase)
  {
    $this->findArticleByIdUseCase = $findArticleByIdUseCase;
  }

  public function execute(Request $request): JsonResponse
  {
    try {
      $articleId = new ArticleId($request->route('articleId'));
      $article = $this->findArticleByIdUseCase->execute($articleId);

      if ($article === null) {
        return response()->json(['error' => '記事が見つかりません'], 404);
      }

      return response()->json([
        'message' => '記事の取得に成功しました',
        'article' => [
          'id' => $article->getId()->toString(),
          'title' => $article->getTitle()->toString(),
          'content' => $article->getContent()->toString(),
          'authorId' => $article->getAuthorId()->toString(),
          'categoryId' => $article->getCategoryId()->toString(),
          'tags' => array_map(fn($tag) => $tag->toString(), $article->getTags()->toArray()),
          'status' => $article->getStatus()->toString(),
          'createdAt' => $article->getCreatedAt()->toString(),
          'updatedAt' => $article->getUpdatedAt()->toString(),
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => '記事の取得に失敗しました: ' . $e->getMessage()], 500);
    }
  }
}
