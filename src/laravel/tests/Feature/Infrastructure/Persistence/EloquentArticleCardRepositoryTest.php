<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\ArticleCard;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\TagName;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\Cursor;
use App\Infrastructure\Persistence\EloquentArticleCardRepository;
use App\Models\EloquentArticle;
use App\Models\EloquentProfile;
use App\Models\User as EloquentUser;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class EloquentArticleCardRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentArticleCardRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentArticleCardRepository();
    config()->set('logging.default', 'stderr');
  }

  public function test_getArticleCards(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentArticle::factory()->count(15)->create(['author_id' => $user->id]);
    EloquentProfile::factory()->create(['user_id' => $user->id]);

    // When
    $result = $this->repository->getArticleCards(10);

    // Then
    $this->assertCount(10, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    $this->assertNotNull($result['nextCursor']);
    $this->assertTrue($result['hasNextPage']);
    $this->assertEquals(15, $result['totalItems']);
  }

  public function test_getByAuthorId(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $author->id]);
    $other = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $other->id]);
    EloquentArticle::factory()->count(5)->create(['author_id' => $author->id]);
    EloquentArticle::factory()->count(3)->create(['author_id' => $other->id]);

    // When
    $result = $this->repository->getByAuthorId(new UserId($author->id), 10);

    // Then
    $this->assertCount(5, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    foreach ($result['data'] as $articleCard) {
      $this->assertEquals($author->name, $articleCard->getAuthor()->getUsername()->toString());
    }
  }

  public function test_getByTag(): void
  {
    // Given
    $tag = EloquentTag::factory()->create();
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    $articlesWithTag = EloquentArticle::factory()->count(3)->create(['author_id' => $user->id]);
    $articlesWithTag->each(function ($article) use ($tag) {
      $article->tags()->attach($tag->id);
    });
    EloquentArticle::factory()->count(2)->create(['author_id' => $user->id]);

    // When
    $result = $this->repository->getByTag(new TagName($tag->name), 10);

    // Then
    $this->assertCount(3, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    foreach ($result['data'] as $articleCard) {
      $this->assertContains($tag->name, $articleCard->getTags()->map(fn($tag) => $tag->toString()));
    }
  }

  public function test_searchByTitle(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->create(['title' => 'Test Article', 'author_id' => $user->id]);
    EloquentArticle::factory()->create(['title' => 'Another Article', 'author_id' => $user->id]);
    EloquentArticle::factory()->create(['title' => 'Something Else', 'author_id' => $user->id]);

    // When
    $result = $this->repository->searchByTitle('Article', 10);

    // Then
    $this->assertCount(2, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    foreach ($result['data'] as $articleCard) {
      $this->assertStringContainsString('Article', $articleCard->getTitle()->toString());
    }
  }

  public function test_getMostLiked(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    $articles = EloquentArticle::factory()->count(5)->create(['author_id' => $user->id]);

    // Simulate likes by updating the likes column
    foreach ($articles as $index => $article) {
      $article->update(['likes' => 5 - $index]);
    }

    // When
    $result = $this->repository->getMostLiked(3);

    // Then
    $this->assertCount(3, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    $this->assertTrue($result['data'][0]->getLikes() >= $result['data'][1]->getLikes());
    $this->assertTrue($result['data'][1]->getLikes() >= $result['data'][2]->getLikes());
  }

  public function test_getLatest(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->count(5)->create(['author_id' => $user->id]);

    // When
    $result = $this->repository->getLatest(3);

    // Then
    $this->assertCount(3, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    $this->assertTrue($result['data'][0]->getCreatedAt()->toString() >= $result['data'][1]->getCreatedAt()->toString());
    $this->assertTrue($result['data'][1]->getCreatedAt()->toString() >= $result['data'][2]->getCreatedAt()->toString());
  }

  public function test_getAfterDate(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    $oldDate = new DateTime('2023-01-01');
    $newDate = new DateTime('2023-06-01');
    EloquentArticle::factory()->count(3)->create(['author_id' => $user->id, 'created_at' => $oldDate->toString()]);
    EloquentArticle::factory()->count(2)->create(['author_id' => $user->id, 'created_at' => $newDate->toString()]);

    // When
    $result = $this->repository->getAfterDate(new DateTime('2023-03-01'), 10);

    // Then
    $this->assertCount(2, $result['data']);
    $this->assertContainsOnlyInstancesOf(ArticleCard::class, $result['data']);
    foreach ($result['data'] as $articleCard) {
      $this->assertTrue(new DateTime($articleCard->getCreatedAt()->toString()) > new DateTime('2023-03-01'));
    }
  }

  public function test_getTotalCount(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentProfile::factory()->create(['user_id' => $user->id]);
    EloquentArticle::factory()->count(7)->create(['author_id' => $user->id]);

    // When
    $count = $this->repository->getTotalCount();

    // Then
    $this->assertEquals(7, $count);
  }
}
