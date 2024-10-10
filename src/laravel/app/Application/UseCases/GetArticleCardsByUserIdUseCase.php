<?php

namespace App\Application\UseCases;

use App\Domain\Entities\ArticleCard;
use App\Domain\Repositories\ArticleCardListRepositoryInterface;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Cursor;

class GetArticleCardsByUserIdUseCase
{
  private $articleCardRepository;

  public function __construct(ArticleCardListRepositoryInterface $articleCardRepository)
  {
    $this->articleCardRepository = $articleCardRepository;
  }

  public function execute(
    UserId $userId,
    int $limit = 10,
    ?Cursor $cursor = null,
    string $sortBy = 'created_at',
    string $sortDirection = 'desc'
  ): array {
    $result = $this->articleCardRepository->getByAuthorId(
      $userId,
      $limit,
      $cursor,
      $sortBy,
      $sortDirection
    );

    return [
      'articles' => $result['data']->map(fn(ArticleCard $article) => $article->toArray()),
      'nextCursor' => $result['nextCursor'],
      'hasNextPage' => $result['hasNextPage'],
      'totalItems' => $result['totalItems'] ?? $this->articleCardRepository->getTotalCount()
    ];
  }
}
