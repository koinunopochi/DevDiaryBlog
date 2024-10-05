<?php

namespace Tests\Feature\Application\Services;

use App\Application\Services\GetAllArticleCategoriesService;
use App\Application\UseCases\GetAllArticleCategoriesUseCase;
use App\Application\UseCases\FindTagByIdUseCase;
use App\Infrastructure\Persistence\EloquentArticleCategoryRepository;
use App\Infrastructure\Persistence\EloquentTagRepository;
use App\Models\EloquentArticleCategory;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllArticleCategoriesServiceTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testCanGetAllArticleCategories(): void
  {
    // Given
    $tags = EloquentTag::factory(3)->create();
    $categories = EloquentArticleCategory::factory(2)->create();

    foreach ($categories as $category) {
      $category->tags()->attach($tags->random(2));
    }

    $getAllCategoriesUseCase = new GetAllArticleCategoriesUseCase(new EloquentArticleCategoryRepository());
    $findTagByIdUseCase = new FindTagByIdUseCase(new EloquentTagRepository());
    $service = new GetAllArticleCategoriesService($getAllCategoriesUseCase, $findTagByIdUseCase);

    // When
    $result = $service->execute();

    // Then
    $this->assertIsArray($result);
    $this->assertCount(2, $result);

    $categoryIds = $categories->pluck('id')->toArray();

    foreach ($result as $index => $categoryData) {
      $this->assertArrayHasKey('id', $categoryData);
      $this->assertArrayHasKey('name', $categoryData);
      $this->assertArrayHasKey('description', $categoryData);
      $this->assertArrayHasKey('tags', $categoryData);

      $this->assertContains($categoryData['id'], $categoryIds);
      $this->assertTrue(in_array($categoryData['name'], $categories->pluck('name')->toArray()));
      $this->assertTrue(in_array($categoryData['description'], $categories->pluck('description')->toArray()));

      $this->assertCount(2, $categoryData['tags']);
      foreach ($categoryData['tags'] as $tagData) {
        $this->assertArrayHasKey('id', $tagData);
        $this->assertArrayHasKey('name', $tagData);
        $this->assertTrue(in_array($tagData['id'], $tags->pluck('id')->toArray()));
      }
    }
  }

  /**
   * @test
   */
  public function testReturnsEmptyArrayWhenNoCategoriesExist(): void
  {
    // Given
    $getAllCategoriesUseCase = new GetAllArticleCategoriesUseCase(new EloquentArticleCategoryRepository());
    $findTagByIdUseCase = new FindTagByIdUseCase(new EloquentTagRepository());
    $service = new GetAllArticleCategoriesService($getAllCategoriesUseCase, $findTagByIdUseCase);

    // When
    $result = $service->execute();

    // Then
    $this->assertIsArray($result);
    $this->assertEmpty($result);
  }
}
