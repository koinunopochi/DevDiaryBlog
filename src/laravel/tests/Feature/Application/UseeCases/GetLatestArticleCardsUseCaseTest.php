<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\GetLatestArticleCardsUseCase;
use App\Infrastructure\Persistence\EloquentArticleCardRepository;
use App\Models\EloquentArticle;
use App\Models\User as EloquentUser;
use App\Models\EloquentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Collection;

class GetLatestArticleCardsUseCaseTest extends TestCase
{
  use RefreshDatabase;

  private GetLatestArticleCardsUseCase $useCase;
  private EloquentArticleCardRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentArticleCardRepository();
    $this->useCase = new GetLatestArticleCardsUseCase($this->repository);
  }

  public function test_it_should_return_latest_article_cards(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->count(5)->create(['author_id' => $user->id]);

    // When
    $result = $this->useCase->execute();

    // Then
    $this->assertIsArray($result);
    $this->assertArrayHasKey('articles', $result);
    $this->assertArrayHasKey('nextCursor', $result);
    $this->assertArrayHasKey('hasNextPage', $result);
    $this->assertArrayHasKey('totalItems', $result);
    $this->assertCount(5, $result['articles']);
    $this->assertInstanceOf(Collection::class, collect($result['articles']));

    foreach ($result['articles'] as $article) {
      $this->assertArticleCardStructure($article);
    }
  }

  public function test_it_should_return_empty_result_when_no_articles_exist(): void
  {
    // When
    $result = $this->useCase->execute();

    // Then
    $this->assertIsArray($result);
    $this->assertEmpty($result['articles']);
    $this->assertNull($result['nextCursor']);
    $this->assertFalse($result['hasNextPage']);
    $this->assertEquals(0, $result['totalItems']);
  }

  public function test_it_should_limit_results(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->count(10)->create(['author_id' => $user->id]);

    // When
    $result = $this->useCase->execute(5);

    // Then
    $this->assertCount(5, $result['articles']);
    $this->assertTrue($result['hasNextPage']);
    $this->assertNotNull($result['nextCursor']);
  }

  public function test_it_should_use_cursor_for_pagination(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->count(15)->create(['author_id' => $user->id]);
    $firstResult = $this->useCase->execute(5);
    $cursor = $firstResult['nextCursor'];

    // When
    $result = $this->useCase->execute(5, $cursor);

    // Then
    $this->assertCount(5, $result['articles']);
    $this->assertEquals($firstResult['articles'][0]['id'], $result['articles'][0]['id']);
  }

  public function test_it_should_sort_by_specified_field(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->count(5)->create(['author_id' => $user->id]);

    // When
    $result = $this->useCase->execute(5, null, 'updated_at');

    // Then
    $updatedAtValues = collect($result['articles'])->pluck('updated_at')->toArray();
    $sortedUpdatedAtValues = $updatedAtValues;
    rsort($sortedUpdatedAtValues); // 降順にソート
    $this->assertEquals($sortedUpdatedAtValues, $updatedAtValues);
  }

  private function assertArticleCardStructure(array $article): void
  {
    $this->assertArrayHasKey('id', $article);
    $this->assertArrayHasKey('title', $article);
    $this->assertArrayHasKey('author', $article);
    $this->assertIsArray($article['author']);
    $this->assertArrayHasKey('username', $article['author']);
    $this->assertArrayHasKey('displayName', $article['author']);
    $this->assertArrayHasKey('profileImage', $article['author']);
    $this->assertArrayHasKey('likes', $article);
    $this->assertArrayHasKey('tags', $article);
    $this->assertIsArray($article['tags']);
    $this->assertArrayHasKey('createdAt', $article);
    $this->assertArrayHasKey('updatedAt', $article);
  }
}
