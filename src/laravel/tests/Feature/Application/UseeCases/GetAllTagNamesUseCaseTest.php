<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\GetAllTagNamesUseCase;
use App\Models\EloquentTag;
use App\Infrastructure\Persistence\EloquentTagRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Collection;

class GetAllTagNamesUseCaseTest extends TestCase
{
  use RefreshDatabase;
  private GetAllTagNamesUseCase $getAllTagNamesUseCase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->getAllTagNamesUseCase = new GetAllTagNamesUseCase(new EloquentTagRepository());
  }

  public function test_it_should_return_collection_of_tag_names()
  {
    // Given
    $tags = EloquentTag::factory()->count(3)->create();

    // When
    $result = $this->getAllTagNamesUseCase->execute();

    // Then
    $this->assertInstanceOf(Collection::class, $result);
    $this->assertCount(3, $result);
    foreach ($tags as $tag) {
      $this->assertContains($tag->name, $result);
    }
  }

  public function test_it_should_return_empty_collection_when_no_tags_exist()
  {
    // When
    $result = $this->getAllTagNamesUseCase->execute();

    // Then
    $this->assertInstanceOf(Collection::class, $result);
    $this->assertEmpty($result);
  }
}
