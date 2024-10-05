<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\GetAllArticleCategoriesUseCase;
use App\Models\EloquentArticleCategory;
use App\Domain\Entities\ArticleCategory;
use App\Infrastructure\Persistence\EloquentArticleCategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Collection;

class GetAllArticleCategoriesUseCaseTest extends TestCase
{
  use RefreshDatabase;

  private GetAllArticleCategoriesUseCase $getAllArticleCategoriesUseCase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->getAllArticleCategoriesUseCase = new GetAllArticleCategoriesUseCase(new EloquentArticleCategoryRepository());
  }

  public function test_it_should_return_collection_of_article_categories()
  {
    // Given
    $categories = EloquentArticleCategory::factory()->count(3)->create();

    // When
    $result = $this->getAllArticleCategoriesUseCase->execute();

    // Then
    $this->assertInstanceOf(Collection::class, $result);
    $this->assertCount(3, $result);
    foreach ($result as $category) {
      $this->assertInstanceOf(ArticleCategory::class, $category);
    }
    foreach ($categories as $category) {
      $this->assertTrue($result->contains(function ($item) use ($category) {
        return $item->getName()->toString() === $category->name;
      }));
    }
  }

  public function test_it_should_return_empty_collection_when_no_categories_exist()
  {
    // When
    $result = $this->getAllArticleCategoriesUseCase->execute();

    // Then
    $this->assertInstanceOf(Collection::class, $result);
    $this->assertEmpty($result);
  }
}

