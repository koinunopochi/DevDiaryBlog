<?php

namespace App\Application\Services;

use App\Application\UseCases\GetAllArticleCategoriesUseCase;
use App\Application\UseCases\FindTagByIdUseCase;
use App\Domain\Entities\ArticleCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GetAllArticleCategoriesService
{
  private GetAllArticleCategoriesUseCase $getAllArticleCategoriesUseCase;
  private FindTagByIdUseCase $findTagByIdUseCase;

  public function __construct(
    GetAllArticleCategoriesUseCase $getAllArticleCategoriesUseCase,
    FindTagByIdUseCase $findTagByIdUseCase
  ) {
    $this->getAllArticleCategoriesUseCase = $getAllArticleCategoriesUseCase;
    $this->findTagByIdUseCase = $findTagByIdUseCase;
  }

  /**
   * @return array
   */
  public function execute(): array
  {
    Log::debug('class : GetAllArticleCategoriesService - method : execute - message : start');
    $categories = $this->getAllArticleCategoriesUseCase->execute();

    return $categories->map(function (ArticleCategory $category) {
      $tagDetails = collect($category->getTags()->map(function ($tagId) {
        $tag = $this->findTagByIdUseCase->execute($tagId);
        return $tag ? [
          'id' => $tag->getId()->toString(),
          'name' => $tag->getName()->toString()
        ] : null;
      }))->filter()->values();

      return [
        'id' => $category->getId()->toString(),
        'name' => $category->getName()->toString(),
        'description' => $category->getDescription()->toString(),
        'tags' => $tagDetails->toArray()
      ];
    })->toArray();
  }
}
