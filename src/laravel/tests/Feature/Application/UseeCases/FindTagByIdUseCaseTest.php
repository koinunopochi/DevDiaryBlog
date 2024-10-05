<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\FindTagByIdUseCase;
use App\Domain\Entities\Tag;
use App\Domain\ValueObjects\TagId;
use App\Infrastructure\Persistence\EloquentTagRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\EloquentTag;

class FindTagByIdUseCaseTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */
  public function testCanGetTagById(): void
  {
    // Given
    $eloquentTag = EloquentTag::factory()->create();
    $tagId = new TagId($eloquentTag->id);

    $useCase = new FindTagByIdUseCase(new EloquentTagRepository());

    // When
    $result = $useCase->execute($tagId);

    // Then
    $this->assertInstanceOf(Tag::class, $result);
    $this->assertEquals($eloquentTag->id, $result->getId()->toString());
    $this->assertEquals($eloquentTag->name, $result->getName()->toString());
  }

  /**
   * @test
   */
  public function testCannotGetTagById(): void
  {
    // Given
    $tagId = new TagId();

    $useCase = new FindTagByIdUseCase(new EloquentTagRepository());

    // When
    $result = $useCase->execute($tagId);

    // Then
    $this->assertNull($result);
  }
}
