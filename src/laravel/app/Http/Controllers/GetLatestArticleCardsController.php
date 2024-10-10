<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetLatestArticleCardsUseCase;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\Cursor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetLatestArticleCardsController extends Controller
{
  private GetLatestArticleCardsUseCase $getLatestArticleCardsUseCase;

  public function __construct(GetLatestArticleCardsUseCase $getLatestArticleCardsUseCase)
  {
    $this->getLatestArticleCardsUseCase = $getLatestArticleCardsUseCase;
  }

  public function execute(Request $request): JsonResponse
  {
    try {
      $limit = $request->input('limit', 10);
      $cursor = $request->input('cursor') ? new Cursor(
        new ArticleId($request->input('cursor.id')),
        new DateTime($request->input('cursor.createdAt')),
        new DateTime($request->input('cursor.updatedAt'))
      ) : null;
      $sortBy = $request->input('sortBy', 'created_at');

      $result = $this->getLatestArticleCardsUseCase->execute(
        $limit,
        $cursor,
        $sortBy
      );

      return response()->json([
        'articles' => $result['articles'],
        'nextCursor' => $result['nextCursor'],
        'hasNextPage' => $result['hasNextPage'],
        'totalItems' => $result['totalItems']
      ]);
    } catch (\Exception $e) {
      Log::error($e);
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
