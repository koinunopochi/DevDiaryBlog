<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\GetArticleCardsByUserIdUseCase;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentArticleCardRepository;
use App\Models\EloquentArticle;
use App\Models\User as EloquentUser;
use App\Models\EloquentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Collection;

class GetArticleCardsByUserIdUseCaseTest extends TestCase
{
  use RefreshDatabase;

  private GetArticleCardsByUserIdUseCase $useCase;
  private EloquentArticleCardRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentArticleCardRepository();
    $this->useCase = new GetArticleCardsByUserIdUseCase($this->repository);
  }

  public function test_it_should_return_article_cards_for_user(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    $userId = new UserId($user->id);
    EloquentArticle::factory()->count(5)->create(['author_id' => $user->id]);

    // When
    $result = $this->useCase->execute($userId);

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
      $this->assertEquals($user->name, $article['author']['username']);
    }
  }

  public function test_it_should_return_empty_result_when_no_articles_exist(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    $userId = new UserId($user->id);

    // When
    $result = $this->useCase->execute($userId);

    // Then
    $this->assertIsArray($result);
    $this->assertEmpty($result['articles']);
    $this->assertNull($result['nextCursor']);
    $this->assertFalse($result['hasNextPage']);
    $this->assertEquals(0, $result['totalItems']);
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
