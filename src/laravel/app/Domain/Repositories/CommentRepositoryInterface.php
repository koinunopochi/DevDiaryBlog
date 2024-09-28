<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CommentContent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CommentRepositoryInterface
{
  /**
   * Get all Comments
   *
   * @return Collection<int, Comment>
   */
  public function all(): Collection;

  /**
   * Find a Comment by id
   *
   * @param CommentId $id
   * @return Comment|null
   */
  public function findById(CommentId $id): ?Comment;

  /**
   * Find Comments by article id
   *
   * @param ArticleId $articleId
   * @return Collection<int, Comment>
   */
  public function findByArticleId(ArticleId $articleId): Collection;

  /**
   * Find Comments by author id
   *
   * @param UserId $authorId
   * @return Collection<int, Comment>
   */
  public function findByAuthorId(UserId $authorId): Collection;

  /**
   * Save a Comment
   *
   * @param Comment $comment
   * @return void
   */
  public function save(Comment $comment): void;

  /**
   * Delete a Comment
   *
   * @param Comment $comment
   * @return void
   */
  public function delete(Comment $comment): void;

  /**
   * Get paginated Comments with optional sorting and filtering
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
   * Count Comments by article id
   *
   * @param ArticleId $articleId
   * @return int
   */
  public function countByArticleId(ArticleId $articleId): int;

  /**
   * Search Comments by content
   *
   * @param CommentContent $content
   * @return Collection<int, Comment>
   */
  public function searchByContent(CommentContent $content): Collection;
}
