<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetArticleCardsByUserIdUseCase;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Cursor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetArticleCardsByUserIdController extends Controller
{
  private GetArticleCardsByUserIdUseCase $getArticleCardsByUserIdUseCase;

  public function __construct(GetArticleCardsByUserIdUseCase $getArticleCardsByUserIdUseCase)
  {
    $this->getArticleCardsByUserIdUseCase = $getArticleCardsByUserIdUseCase;
  }

  public function execute(Request $request, string $userId): JsonResponse
  {
    try {
      $limit = $request->input('limit', 10);
      $cursor = $request->input('cursor') ? new Cursor(
        new ArticleId($request->input('cursor.id')),
        new DateTime($request->input('cursor.createdAt')),
        new DateTime($request->input('cursor.updatedAt'))
      ) : null;
      $sortBy = $request->input('sortBy', 'created_at');
      $sortDirection = $request->input('sortDirection', 'desc');

      $result = $this->getArticleCardsByUserIdUseCase->execute(
        new UserId($userId),
        $limit,
        $cursor,
        $sortBy,
        $sortDirection
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
