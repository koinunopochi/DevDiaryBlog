<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ArticleCard;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\TagName;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\Cursor;
use Illuminate\Support\Collection;

interface ArticleCardListRepositoryInterface
{
  /**
   * Get ArticleCards with cursor-based pagination
   *
   * @param int $limit
   * @param Cursor|null $cursor
   * @param array $filters
   * @param string $sortBy
   * @param string $sortDirection
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool, totalItems: int}
   */
  public function getArticleCards(
    int $limit,
    ?Cursor $cursor = null,
    array $filters = [],
    string $sortBy = 'createdAt',
    string $sortDirection = 'desc'
  ): array;

  /**
   * Get ArticleCards by author id
   *
   * @param UserId $authorId
   * @param int $limit
   * @param Cursor|null $cursor
   * @param string $sortBy
   * @param string $sortDirection
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool}
   */
  public function getByAuthorId(
    UserId $authorId,
    int $limit,
    ?Cursor $cursor = null,
    string $sortBy = 'createdAt',
    string $sortDirection = 'desc'
  ): array;

  /**
   * Get ArticleCards by tag
   *
   * @param TagName $tagName
   * @param int $limit
   * @param Cursor|null $cursor
   * @param string $sortBy
   * @param string $sortDirection
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool}
   */
  public function getByTag(
    TagName $tagName,
    int $limit,
    ?Cursor $cursor = null,
    string $sortBy = 'createdAt',
    string $sortDirection = 'desc'
  ): array;

  /**
   * Search ArticleCards by title
   *
   * @param string $searchTerm
   * @param int $limit
   * @param Cursor|null $cursor
   * @param string $sortBy
   * @param string $sortDirection
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool}
   */
  public function searchByTitle(
    string $searchTerm,
    int $limit,
    ?Cursor $cursor = null,
    string $sortBy = 'createdAt',
    string $sortDirection = 'desc'
  ): array;

  /**
   * Get most liked ArticleCards
   *
   * @param int $limit
   * @param Cursor|null $cursor
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool}
   */
  public function getMostLiked(int $limit, ?Cursor $cursor = null): array;

  /**
   * Get latest ArticleCards
   *
   * @param int $limit
   * @param Cursor|null $cursor
   * @param string $sortBy
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool}
   */
  public function getLatest(
    int $limit,
    ?Cursor $cursor = null,
    string $sortBy = 'createdAt'
  ): array;

  /**
   * Get ArticleCards created or updated after a specific date
   *
   * @param DateTime $date
   * @param int $limit
   * @param Cursor|null $cursor
   * @param string $dateField
   * @return array{data: Collection<int, ArticleCard>, nextCursor: ?Cursor, hasNextPage: bool}
   */
  public function getAfterDate(
    DateTime $date,
    int $limit,
    ?Cursor $cursor = null,
    string $dateField = 'createdAt'
  ): array;

  /**
   * Get total count of ArticleCards
   *
   * @param array $filters
   * @return int
   */
  public function getTotalCount(array $filters = []): int;
}
