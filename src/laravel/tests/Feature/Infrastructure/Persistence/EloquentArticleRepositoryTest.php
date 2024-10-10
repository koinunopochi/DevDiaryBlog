<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Article;
use App\Domain\Entities\DraftArticle;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\ArticleTagIdCollection;
use App\Domain\ValueObjects\TagId;
use App\Infrastructure\Persistence\EloquentArticleRepository;
use App\Models\EloquentArticle;
use App\Models\User as EloquentUser;
use App\Models\EloquentArticleCategory;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentArticleRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentArticleRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentArticleRepository();
    // config()->set('logging.default', 'stderr');
  }

  public function test_all(): void
  {
    // Given
    EloquentArticle::factory()->count(3)->create();

    // When
    $articles = $this->repository->all();

    // Then
    $this->assertCount(3, $articles);
    $this->assertContainsOnlyInstancesOf(Article::class, $articles);
  }

  public function test_findById(): void
  {
    // Given
    $createdArticle = EloquentArticle::factory()->create();

    // When
    $article = $this->repository->findById(new ArticleId($createdArticle->id));

    // Then
    $this->assertNotNull($article);
    $this->assertEquals($createdArticle->id, $article->getId()->toString());
    $this->assertEquals($createdArticle->title, $article->getTitle()->toString());
  }

  public function test_findByAuthorId(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    EloquentArticle::factory()->count(3)->create(['author_id' => $author->id]);
    EloquentArticle::factory()->count(2)->create();

    // When
    $articles = $this->repository->findByAuthorId(new UserId($author->id));

    // Then
    $this->assertCount(3, $articles);
    $this->assertContainsOnlyInstancesOf(Article::class, $articles);
    foreach ($articles as $article) {
      $this->assertEquals($author->id, $article->getAuthorId()->toString());
    }
  }

  public function test_findByCategoryId(): void
  {
    // Given
    $category = EloquentArticleCategory::factory()->create();
    EloquentArticle::factory()->count(2)->create(['category_id' => $category->id]);
    EloquentArticle::factory()->count(3)->create();

    // When
    $articles = $this->repository->findByCategoryId(new ArticleCategoryId($category->id));

    // Then
    $this->assertCount(2, $articles);
    $this->assertContainsOnlyInstancesOf(Article::class, $articles);
    foreach ($articles as $article) {
      $this->assertEquals($category->id, $article->getCategoryId()->toString());
    }
  }

  public function test_findByStatus(): void
  {
    // Given
    EloquentArticle::factory()->count(2)->create(['status' => 'Published']);
    EloquentArticle::factory()->count(3)->create(['status' => 'Draft']);

    // When
    $articles = $this->repository->findByStatus(new ArticleStatus(ArticleStatus::STATUS_PUBLISHED));

    // Then
    $this->assertCount(2, $articles);
    $this->assertContainsOnlyInstancesOf(Article::class, $articles);
    foreach ($articles as $article) {
      $this->assertEquals('Published', $article->getStatus()->toString());
    }
  }

  public function test_searchByTitle(): void
  {
    // Given
    EloquentArticle::factory()->create(['title' => 'Test Article']);
    EloquentArticle::factory()->create(['title' => 'Another Article']);
    EloquentArticle::factory()->create(['title' => 'Something Else']);

    // When
    $articles = $this->repository->searchByTitle(new ArticleTitle('Article'));

    // Then
    $this->assertCount(2, $articles);
    $this->assertContainsOnlyInstancesOf(Article::class, $articles);
    foreach ($articles as $article) {
      $this->assertStringContainsString('Article', $article->getTitle()->toString());
    }
  }

  public function test_save_create_new(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    $category = EloquentArticleCategory::factory()->create();
    $newArticle = new Article(
      new ArticleId(),
      new ArticleTitle('New Article'),
      new ArticleContent('Content'),
      new UserId($author->id),
      new ArticleCategoryId($category->id),
      new ArticleTagIdCollection([]),
      new ArticleStatus(ArticleStatus::STATUS_DRAFT),
      new DateTime(),
      new DateTime()
    );

    // When
    $this->repository->save($newArticle);

    // Then
    $articleFromDatabase = EloquentArticle::find($newArticle->getId()->toString());

    $this->assertNotNull($articleFromDatabase);
    $this->assertEquals($newArticle->getId()->toString(), $articleFromDatabase->id);
    $this->assertEquals($newArticle->getTitle()->toString(), $articleFromDatabase->title);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingArticle = EloquentArticle::factory()->create();

    // When
    $updatedArticle = new Article(
      new ArticleId($existingArticle->id),
      new ArticleTitle('Updated Article'),
      new ArticleContent($existingArticle->content),
      new UserId($existingArticle->author_id),
      new ArticleCategoryId($existingArticle->category_id),
      new ArticleTagIdCollection([]),
      new ArticleStatus($existingArticle->status),
      new DateTime($existingArticle->created_at),
      new DateTime()
    );

    $this->repository->save($updatedArticle);

    // Then
    $articleFromDatabase = EloquentArticle::find($existingArticle->id);

    $this->assertNotNull($articleFromDatabase);
    $this->assertEquals($updatedArticle->getId()->toString(), $articleFromDatabase->id);
    $this->assertEquals($updatedArticle->getTitle()->toString(), $articleFromDatabase->title);
  }

  public function test_delete(): void
  {
    // Given
    $articleToDelete = EloquentArticle::factory()->create();

    // When
    $article = $this->repository->findById(new ArticleId($articleToDelete->id));
    $this->assertNotNull($article);

    $this->repository->delete($article);

    // Then
    $this->assertDatabaseMissing('articles', [
      'id' => $articleToDelete->id
    ]);
  }

  public function test_getPaginated(): void
  {
    // Given
    EloquentArticle::factory()->count(20)->create();

    // When
    $paginatedArticles = $this->repository->getPaginated(1, 10);

    // Then
    $this->assertCount(10, $paginatedArticles->items());
    $this->assertEquals(20, $paginatedArticles->total());
    $this->assertEquals(2, $paginatedArticles->lastPage());
    $this->assertContainsOnlyInstancesOf(Article::class, $paginatedArticles->items());
  }

  public function test_countByStatus(): void
  {
    // Given
    EloquentArticle::factory()->count(3)->create(['status' => 'published']);
    EloquentArticle::factory()->count(2)->create(['status' => 'draft']);

    // When
    $publishedCount = $this->repository->countByStatus(new ArticleStatus(ArticleStatus::STATUS_PUBLISHED));
    $draftCount = $this->repository->countByStatus(new ArticleStatus(ArticleStatus::STATUS_DRAFT));

    // Then
    $this->assertEquals(3, $publishedCount);
    $this->assertEquals(2, $draftCount);
  }

  public function test_findByTagId(): void
  {
    // Given
    $tag = EloquentTag::factory()->create();
    $articlesWithTag = EloquentArticle::factory()->count(2)->create();
    $articlesWithTag->each(function ($article) use ($tag) {
      $article->tags()->attach($tag->id);
    });
    EloquentArticle::factory()->count(3)->create();

    // When
    $articles = $this->repository->findByTagId($tag->id);

    // Then
    $this->assertCount(2, $articles);
    $this->assertContainsOnlyInstancesOf(Article::class, $articles);
    foreach ($articles as $article) {
      $tagIds = $article->getTags()->map(fn(TagId $tagId) => $tagId->toString());
      $this->assertContains($tag->id, $tagIds);
    }
  }

  public function test_reserveDraftArticle(): void
  {
    // Given
    $id = new ArticleId();
    $status = new ArticleStatus(ArticleStatus::STATUS_DRAFT);
    $createdAt = new DateTime("2024-09-29 05:25:37");
    $draftArticle = new DraftArticle($id, $status, $createdAt);

    // When
    $this->repository->reserveDraftArticle($draftArticle);

    // Then
    $this->assertDatabaseHas('articles', [
      'id' => $id->toString(),
      'status' => $status->toString(),
      'created_at' => "2024-09-29 05:25:37", // note: DBの保存形式に合わせる
    ]);
  }

  public function test_convertDraftToArticle(): void
  {
    // Given
    $draftId = new ArticleId();
    $draftStatus = new ArticleStatus(ArticleStatus::STATUS_DRAFT);
    $createdAt = new DateTime();
    $draftArticle = new DraftArticle($draftId, $draftStatus, $createdAt);
    $this->repository->reserveDraftArticle($draftArticle);

    $author = EloquentUser::factory()->create();
    $category = EloquentArticleCategory::factory()->create();
    $tag = EloquentTag::factory()->create();

    $fullArticle = new Article(
      $draftId,
      new ArticleTitle('Full Article Title'),
      new ArticleContent('Full Article Content'),
      new UserId($author->id),
      new ArticleCategoryId($category->id),
      new ArticleTagIdCollection([new TagId($tag->id)]),
      new ArticleStatus(ArticleStatus::STATUS_PUBLISHED),
      $createdAt,
      new DateTime()
    );

    // When
    $this->repository->convertDraftToArticle($draftArticle, $fullArticle);

    // Then
    $this->assertDatabaseHas('articles', [
      'id' => $draftId->toString(),
      'title' => 'Full Article Title',
      'content' => 'Full Article Content',
      'author_id' => $author->id,
      'category_id' => $category->id,
      'status' => ArticleStatus::STATUS_PUBLISHED,
    ]);

    $articleFromDb = EloquentArticle::find($draftId->toString());
    $this->assertNotNull($articleFromDb);
    $this->assertCount(1, $articleFromDb->tags);
    $this->assertEquals($tag->id, $articleFromDb->tags->first()->id);
  }
}
