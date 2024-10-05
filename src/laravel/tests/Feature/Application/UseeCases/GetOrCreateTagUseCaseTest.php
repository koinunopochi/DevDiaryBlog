<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\GetOrCreateTagUseCase;
use App\Domain\Entities\Tag;
use App\Domain\ValueObjects\TagName;
use App\Infrastructure\Persistence\EloquentTagRepository;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetOrCreateTagUseCaseTest extends TestCase
{
  use RefreshDatabase;

  private GetOrCreateTagUseCase $getOrCreateTagUseCase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->getOrCreateTagUseCase = new GetOrCreateTagUseCase(new EloquentTagRepository());
  }

  public function test_it_should_return_existing_tag_when_tag_exists()
  {
    // Given
    $existingTag = EloquentTag::factory()->create();
    $tagName = new TagName($existingTag->name);

    // When
    $result = $this->getOrCreateTagUseCase->execute($tagName);

    // Then
    $this->assertInstanceOf(Tag::class, $result);
    $this->assertEquals($existingTag->id, $result->getId()->toString());
    $this->assertEquals($existingTag->name, $result->getName()->toString());
  }

  public function test_it_should_create_and_return_new_tag_when_tag_does_not_exist()
  {
    // Given
    $newTagName = 'new-tag';
    $tagName = new TagName($newTagName);

    // When
    $result = $this->getOrCreateTagUseCase->execute($tagName);

    // Then
    $this->assertInstanceOf(Tag::class, $result);
    $this->assertEquals($newTagName, $result->getName()->toString());

    // Verify the tag was actually saved to the database
    $this->assertDatabaseHas('tags', [
      'id' => $result->getId()->toString(),
      'name' => $newTagName
    ]);
  }

  public function test_it_should_not_create_duplicate_tag_when_executed_twice_with_same_name()
  {
    // Given
    $tagName = new TagName('unique-tag');

    // When
    $result1 = $this->getOrCreateTagUseCase->execute($tagName);
    $result2 = $this->getOrCreateTagUseCase->execute($tagName);

    // Then
    $this->assertEquals($result1->getId()->toString(), $result2->getId()->toString());
    $this->assertCount(1, EloquentTag::where('name', 'unique-tag')->get());
  }
}
