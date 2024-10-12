<?php

namespace App\Http\Controllers;

use App\Application\UseCases\FindUserByNameUseCase;
use App\Application\UseCases\GetArticleCardsByUserIdUseCase;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Cursor;
use App\Domain\ValueObjects\Username;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetArticleCardsByUserIdOrUsernameController extends Controller
{
  private GetArticleCardsByUserIdUseCase $getArticleCardsByUserIdUseCase;
  private FindUserByNameUseCase $findUserByNameUseCase;

  public function __construct(
    GetArticleCardsByUserIdUseCase $getArticleCardsByUserIdUseCase,
    FindUserByNameUseCase $findUserByNameUseCase
  ) {
    $this->getArticleCardsByUserIdUseCase = $getArticleCardsByUserIdUseCase;
    $this->findUserByNameUseCase = $findUserByNameUseCase;
  }

  public function execute(Request $request, string $userIdOrUsername): JsonResponse
  {
    try {
      $userId = $this->resolveUserId($userIdOrUsername);

      $limit = $request->query('limit', 10);
      $cursor = $this->buildCursor($request);
      $sortBy = $request->query('sortBy', 'created_at');
      $sortDirection = $request->query('sortDirection', 'desc');

      $result = $this->getArticleCardsByUserIdUseCase->execute(
        new UserId($userId),
        $limit,
        $cursor,
        $sortBy,
        $sortDirection
      );
      Log::info('$result[nextCursor]: ' . json_encode($result['nextCursor']));

      return response()->json([
        'articles' => $result['articles'],
        'nextCursor' => Cursor::fromJson(json_encode($result['nextCursor']))->toBase64(),
        'hasNextPage' => $result['hasNextPage'],
        'totalItems' => $result['totalItems']
      ]);
    } catch (\Exception $e) {
      Log::error($e);
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  private function resolveUserId(string $userIdOrUsername): string
  {
    try {
      new UserId($userIdOrUsername);
      return $userIdOrUsername;
    } catch (\Throwable $th) {
      return $this->findUserByNameUseCase->execute(new Username($userIdOrUsername))->getUserId()->toString();
    }
  }

  private function buildCursor(Request $request): ?Cursor
  {
    $cursor = $request->query('cursor');

    if ($cursor) {
      return Cursor::fromBase64($cursor);
    }

    return null;
  }
}
