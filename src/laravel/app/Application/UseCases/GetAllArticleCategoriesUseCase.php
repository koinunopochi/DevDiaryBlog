<?php

namespace App\Application\UseCases;

use App\Domain\Entities\ArticleCategory;
use App\Domain\Repositories\ArticleCategoryRepositoryInterface;
use Illuminate\Support\Collection;

class GetAllArticleCategoriesUseCase
{
  private $articleCategoryRepository;

  public function __construct(ArticleCategoryRepositoryInterface $articleCategoryRepository)
  {
    $this->articleCategoryRepository = $articleCategoryRepository;
  }

  /**
   * @return Collection<int, ArticleCategory>
   */
  public function execute(): Collection
  {
    return $this->articleCategoryRepository->all();
  }
}
