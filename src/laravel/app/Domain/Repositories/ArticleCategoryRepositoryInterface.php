<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ArticleCategory;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleCategoryName;
use Illuminate\Support\Collection;

interface ArticleCategoryRepositoryInterface
{
  /**
   * Get all ArticleCategories
   *
   * @return Collection<int, ArticleCategory>
   */
  public function all(): Collection;

  /**
   * Find an ArticleCategory by id
   *
   * @param ArticleCategoryId $id
   * @return ArticleCategory|null
   */
  public function findById(ArticleCategoryId $id): ?ArticleCategory;

  /**
   * Find an ArticleCategory by name
   *
   * @param ArticleCategoryName $name
   * @return ArticleCategory|null
   */
  public function findByName(ArticleCategoryName $name): ?ArticleCategory;

  /**
   * Save an ArticleCategory
   *
   * @param ArticleCategory $articleCategory
   * @return void
   */
  public function save(ArticleCategory $articleCategory): void;

  /**
   * Delete an ArticleCategory
   *
   * @param ArticleCategory $articleCategory
   * @return void
   */
  public function delete(ArticleCategory $articleCategory): void;

  /**
   * Determine if an ArticleCategory exists by name
   *
   * @param ArticleCategoryName $name
   * @return bool
   */
  public function existsByName(ArticleCategoryName $name): bool;
}
