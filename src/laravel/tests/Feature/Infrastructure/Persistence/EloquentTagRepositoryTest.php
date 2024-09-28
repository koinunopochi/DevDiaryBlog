<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Tag;
use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;
use App\Infrastructure\Persistence\EloquentTagRepository;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentTagRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentTagRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentTagRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentTag::factory()->count(3)->create();

    // When
    $tags = $this->repository->all();

    // Then
    $this->assertCount(3, $tags);
    $this->assertContainsOnlyInstancesOf(Tag::class, $tags);
  }

  public function test_findById(): void
  {
    // Given
    $createdTag = EloquentTag::factory()->create();

    // When
    $tag = $this->repository->findById(new TagId($createdTag->id));

    // Then
    $this->assertNotNull($tag);
    $this->assertEquals($createdTag->id, $tag->getId()->toString());
    $this->assertEquals($createdTag->name, $tag->getName()->toString());
  }

  public function test_findByName(): void
  {
    // Given
    $createdTag = EloquentTag::factory()->create();

    // When
    $tag = $this->repository->findByName(new TagName($createdTag->name));

    // Then
    $this->assertNotNull($tag);
    $this->assertEquals($createdTag->id, $tag->getId()->toString());
    $this->assertEquals($createdTag->name, $tag->getName()->toString());
  }

  public function test_save_create_new(): void
  {
    // Given
    $newTag = new Tag(
      new TagId(),
      new TagName('NewTag')
    );

    // When
    $this->repository->save($newTag);

    // Then
    $tagFromDatabase = EloquentTag::find($newTag->getId()->toString());

    $this->assertNotNull($tagFromDatabase);
    $this->assertEquals($newTag->getId()->toString(), $tagFromDatabase->id);
    $this->assertEquals($newTag->getName()->toString(), $tagFromDatabase->name);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingTag = EloquentTag::factory()->create();

    // When
    $updatedTag = new Tag(
      new TagId($existingTag->id),
      new TagName('UpdatedTag')
    );

    $this->repository->save($updatedTag);

    // Then
    $tagFromDatabase = EloquentTag::find($existingTag->id);

    $this->assertNotNull($tagFromDatabase);
    $this->assertEquals($updatedTag->getId()->toString(), $tagFromDatabase->id);
    $this->assertEquals($updatedTag->getName()->toString(), $tagFromDatabase->name);
  }

  public function test_delete(): void
  {
    // Given
    $tagToDelete = EloquentTag::factory()->create();

    // When
    $tag = $this->repository->findById(new TagId($tagToDelete->id));
    $this->assertNotNull($tag);

    $this->repository->delete($tag);

    // Then
    $this->assertDatabaseMissing('tags', [
      'id' => $tagToDelete->id
    ]);
  }

  public function test_existsByName(): void
  {
    // Given
    $existingTag = EloquentTag::factory()->create();

    // When & Then
    $this->assertTrue($this->repository->existsByName(new TagName($existingTag->name)));
    $this->assertFalse($this->repository->existsByName(new TagName('NonExistentTag')));
  }
}
