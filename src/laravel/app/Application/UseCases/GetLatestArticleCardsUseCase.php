<?php

namespace App\Application\UseCases;

use App\Domain\Entities\ArticleCard;
use App\Domain\Repositories\ArticleCardListRepositoryInterface;
use App\Domain\ValueObjects\Cursor;

class GetLatestArticleCardsUseCase
{
  private $articleCardRepository;

  public function __construct(ArticleCardListRepositoryInterface $articleCardRepository)
  {
    $this->articleCardRepository = $articleCardRepository;
  }

  /**
   * 最近投稿された記事を取得する
   *
   * @param int $limit 取得する記事の数
   * @param Cursor|null $cursor ページネーション用のカーソル
   * @param string $sortBy ソートするフィールド（デフォルトは作成日時）
   * @return array{
   *   articles: array<int, array>,
   *   nextCursor: ?Cursor,
   *   hasNextPage: bool,
   *   totalItems: int
   * }
   */
  public function execute(
    int $limit = 10,
    ?Cursor $cursor = null,
    string $sortBy = 'created_at'
  ): array {
    $result = $this->articleCardRepository->getLatest(
      $limit,
      $cursor,
      $sortBy
    );

    return [
      'articles' => $result['data']->map(fn(ArticleCard $article) => $article->toArray())->toArray(),
      'nextCursor' => $result['nextCursor'],
      'hasNextPage' => $result['hasNextPage'],
      'totalItems' => $result['totalItems'] ?? $this->articleCardRepository->getTotalCount()
    ];
  }
}
