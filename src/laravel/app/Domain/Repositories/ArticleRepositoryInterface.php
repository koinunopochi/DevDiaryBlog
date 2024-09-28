<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Article;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ArticleRepositoryInterface
{
  /**
   * Get all Articles
   *
   * @return Collection<int, Article>
   */
  public function all(): Collection;

  /**
   * Find an Article by id
   *
   * @param ArticleId $id
   * @return Article|null
   */
  public function findById(ArticleId $id): ?Article;

  /**
   * Find Articles by author id
   *
   * @param UserId $authorId
   * @return Collection<int, Article>
   */
  public function findByAuthorId(UserId $authorId): Collection;

  /**
   * Find Articles by category id
   *
   * @param ArticleCategoryId $categoryId
   * @return Collection<int, Article>
   */
  public function findByCategoryId(ArticleCategoryId $categoryId): Collection;

  /**
   * Find Articles by status
   *
   * @param ArticleStatus $status
   * @return Collection<int, Article>
   */
  public function findByStatus(ArticleStatus $status): Collection;

  /**
   * Search Articles by title
   *
   * @param ArticleTitle $title
   * @return Collection<int, Article>
   */
  public function searchByTitle(ArticleTitle $title): Collection;

  /**
   * Save an Article
   *
   * @param Article $article
   * @return void
   */
  public function save(Article $article): void;

  /**
   * Delete an Article
   *
   * @param Article $article
   * @return void
   */
  public function delete(Article $article): void;

  /**
   * Get paginated Articles with optional sorting and filtering
   *
   * @param int $page
   * @param int $perPage
   * @param array $filters
   * @param string $sortBy
   * @param string $sortDirection
   * @return LengthAwarePaginator
   */
  public function getPaginated(
    int $page,
    int $perPage,
    array $filters = [],
    string $sortBy = 'created_at',
    string $sortDirection = 'desc'
  ): LengthAwarePaginator;

  /**
   * Count Articles by status
   *
   * @param ArticleStatus $status
   * @return int
   */
  public function countByStatus(ArticleStatus $status): int;

  /**
   * Find Articles by tag id
   *
   * @param string $tagId
   * @return Collection<int, Article>
   */
  public function findByTagId(string $tagId): Collection;
}
