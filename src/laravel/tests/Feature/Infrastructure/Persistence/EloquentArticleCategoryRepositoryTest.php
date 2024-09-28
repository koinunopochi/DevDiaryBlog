<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\ArticleCategory;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleCategoryName;
use App\Domain\ValueObjects\ArticleCategoryDescription;
use App\Domain\ValueObjects\ArticleCategoryTagCollection;
use App\Domain\ValueObjects\TagId;
use App\Infrastructure\Persistence\EloquentArticleCategoryRepository;
use App\Models\EloquentArticleCategory;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentArticleCategoryRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentArticleCategoryRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentArticleCategoryRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentArticleCategory::factory()->count(3)->create();

    // When
    $categories = $this->repository->all();

    // Then
    $this->assertCount(3, $categories);
    $this->assertContainsOnlyInstancesOf(ArticleCategory::class, $categories);
  }

  public function test_findById(): void
  {
    // Given
    $createdCategory = EloquentArticleCategory::factory()->create();

    // When
    $category = $this->repository->findById(new ArticleCategoryId($createdCategory->id));

    // Then
    $this->assertNotNull($category);
    $this->assertEquals($createdCategory->id, $category->getId()->toString());
    $this->assertEquals($createdCategory->name, $category->getName()->toString());
    $this->assertEquals($createdCategory->description, $category->getDescription()->toString());
  }

  public function test_findByName(): void
  {
    // Given
    $createdCategory = EloquentArticleCategory::factory()->create();

    // When
    $category = $this->repository->findByName(new ArticleCategoryName($createdCategory->name));

    // Then
    $this->assertNotNull($category);
    $this->assertEquals($createdCategory->id, $category->getId()->toString());
    $this->assertEquals($createdCategory->name, $category->getName()->toString());
    $this->assertEquals($createdCategory->description, $category->getDescription()->toString());
  }

  public function test_save_create_new(): void
  {
    // Given
    $newCategory = new ArticleCategory(
      new ArticleCategoryId(),
      new ArticleCategoryName('NewCategory'),
      new ArticleCategoryDescription('New Description'),
      new ArticleCategoryTagCollection([])
    );

    // When
    $this->repository->save($newCategory);

    // Then
    $categoryFromDatabase = EloquentArticleCategory::find($newCategory->getId()->toString());

    $this->assertNotNull($categoryFromDatabase);
    $this->assertEquals($newCategory->getId()->toString(), $categoryFromDatabase->id);
    $this->assertEquals($newCategory->getName()->toString(), $categoryFromDatabase->name);
    $this->assertEquals($newCategory->getDescription()->toString(), $categoryFromDatabase->description);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingCategory = EloquentArticleCategory::factory()->create();

    // When
    $updatedCategory = new ArticleCategory(
      new ArticleCategoryId($existingCategory->id),
      new ArticleCategoryName('UpdatedCategory'),
      new ArticleCategoryDescription('Updated Description'),
      new ArticleCategoryTagCollection([])
    );

    $this->repository->save($updatedCategory);

    // Then
    $categoryFromDatabase = EloquentArticleCategory::find($existingCategory->id);

    $this->assertNotNull($categoryFromDatabase);
    $this->assertEquals($updatedCategory->getId()->toString(), $categoryFromDatabase->id);
    $this->assertEquals($updatedCategory->getName()->toString(), $categoryFromDatabase->name);
    $this->assertEquals($updatedCategory->getDescription()->toString(), $categoryFromDatabase->description);
  }

  public function test_delete(): void
  {
    // Given
    $categoryToDelete = EloquentArticleCategory::factory()->create();

    // When
    $category = $this->repository->findById(new ArticleCategoryId($categoryToDelete->id));
    $this->assertNotNull($category);

    $this->repository->delete($category);

    // Then
    $this->assertDatabaseMissing('article_categories', [
      'id' => $categoryToDelete->id
    ]);
  }

  public function test_existsByName(): void
  {
    // Given
    $existingCategory = EloquentArticleCategory::factory()->create();

    // When & Then
    $this->assertTrue($this->repository->existsByName(new ArticleCategoryName($existingCategory->name)));
    $this->assertFalse($this->repository->existsByName(new ArticleCategoryName('NonExistentCategory')));
  }

  public function test_save_with_tags(): void
  {
    // Given
    $tag1 = EloquentTag::factory()->create();
    $tag2 = EloquentTag::factory()->create();

    $newCategory = new ArticleCategory(
      new ArticleCategoryId(),
      new ArticleCategoryName('CategoryWithTags'),
      new ArticleCategoryDescription('Category with tags'),
      new ArticleCategoryTagCollection([
        new TagId($tag1->id),
        new TagId($tag2->id),
      ])
    );

    // When
    $this->repository->save($newCategory);

    // Then
    $categoryFromDatabase = EloquentArticleCategory::with('tags')->find($newCategory->getId()->toString());

    $this->assertNotNull($categoryFromDatabase);
    $this->assertCount(2, $categoryFromDatabase->tags);
    $this->assertTrue($categoryFromDatabase->tags->contains('id', $tag1->id));
    $this->assertTrue($categoryFromDatabase->tags->contains('id', $tag2->id));
  }
}
