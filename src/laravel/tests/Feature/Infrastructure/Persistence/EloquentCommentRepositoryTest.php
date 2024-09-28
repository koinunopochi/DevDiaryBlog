<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CommentContent;
use App\Domain\ValueObjects\DateTime;
use App\Infrastructure\Persistence\EloquentCommentRepository;
use App\Models\EloquentComment;
use App\Models\EloquentArticle;
use App\Models\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentCommentRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentCommentRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentCommentRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentComment::factory()->count(3)->create();

    // When
    $comments = $this->repository->all();

    // Then
    $this->assertCount(3, $comments);
    $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
  }

  public function test_findById(): void
  {
    // Given
    $createdComment = EloquentComment::factory()->create();

    // When
    $comment = $this->repository->findById(new CommentId($createdComment->id));

    // Then
    $this->assertNotNull($comment);
    $this->assertEquals($createdComment->id, $comment->getId()->toString());
    $this->assertEquals($createdComment->content, $comment->getContent()->toString());
  }

  public function test_findByArticleId(): void
  {
    // Given
    $article = EloquentArticle::factory()->create();
    EloquentComment::factory()->count(3)->create(['article_id' => $article->id]);
    EloquentComment::factory()->count(2)->create();

    // When
    $comments = $this->repository->findByArticleId(new ArticleId($article->id));

    // Then
    $this->assertCount(3, $comments);
    $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
    foreach ($comments as $comment) {
      $this->assertEquals($article->id, $comment->getArticleId()->toString());
    }
  }

  public function test_findByAuthorId(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    EloquentComment::factory()->count(2)->create(['author_id' => $author->id]);
    EloquentComment::factory()->count(3)->create();

    // When
    $comments = $this->repository->findByAuthorId(new UserId($author->id));

    // Then
    $this->assertCount(2, $comments);
    $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
    foreach ($comments as $comment) {
      $this->assertEquals($author->id, $comment->getAuthorId()->toString());
    }
  }

  public function test_save_create_new(): void
  {
    // Given
    $article = EloquentArticle::factory()->create();
    $author = EloquentUser::factory()->create();
    $newComment = new Comment(
      new CommentId(),
      new ArticleId($article->id),
      new UserId($author->id),
      new CommentContent('New Comment'),
      new DateTime(),
      new DateTime()
    );

    // When
    $this->repository->save($newComment);

    // Then
    $commentFromDatabase = EloquentComment::find($newComment->getId()->toString());

    $this->assertNotNull($commentFromDatabase);
    $this->assertEquals($newComment->getId()->toString(), $commentFromDatabase->id);
    $this->assertEquals($newComment->getContent()->toString(), $commentFromDatabase->content);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingComment = EloquentComment::factory()->create();

    // When
    $updatedComment = new Comment(
      new CommentId($existingComment->id),
      new ArticleId($existingComment->article_id),
      new UserId($existingComment->author_id),
      new CommentContent('Updated Comment'),
      new DateTime($existingComment->created_at),
      new DateTime()
    );

    $this->repository->save($updatedComment);

    // Then
    $commentFromDatabase = EloquentComment::find($existingComment->id);

    $this->assertNotNull($commentFromDatabase);
    $this->assertEquals($updatedComment->getId()->toString(), $commentFromDatabase->id);
    $this->assertEquals($updatedComment->getContent()->toString(), $commentFromDatabase->content);
  }

  public function test_delete(): void
  {
    // Given
    $commentToDelete = EloquentComment::factory()->create();

    // When
    $comment = $this->repository->findById(new CommentId($commentToDelete->id));
    $this->assertNotNull($comment);

    $this->repository->delete($comment);

    // Then
    $this->assertDatabaseMissing('comments', [
      'id' => $commentToDelete->id
    ]);
  }

  public function test_getPaginated(): void
  {
    // Given
    EloquentComment::factory()->count(20)->create();

    // When
    $paginatedComments = $this->repository->getPaginated(1, 10);

    // Then
    $this->assertCount(10, $paginatedComments->items());
    $this->assertEquals(20, $paginatedComments->total());
    $this->assertEquals(2, $paginatedComments->lastPage());
    $this->assertContainsOnlyInstancesOf(Comment::class, $paginatedComments->items());
  }

  public function test_countByArticleId(): void
  {
    // Given
    $article = EloquentArticle::factory()->create();
    EloquentComment::factory()->count(3)->create(['article_id' => $article->id]);
    EloquentComment::factory()->count(2)->create();

    // When
    $count = $this->repository->countByArticleId(new ArticleId($article->id));

    // Then
    $this->assertEquals(3, $count);
  }

  public function test_searchByContent(): void
  {
    // Given
    EloquentComment::factory()->create(['content' => 'Test Comment']);
    EloquentComment::factory()->create(['content' => 'Another Comment']);
    EloquentComment::factory()->create(['content' => 'Something Else']);

    // When
    $comments = $this->repository->searchByContent(new CommentContent('Comment'));

    // Then
    $this->assertCount(2, $comments);
    $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
    foreach ($comments as $comment) {
      $this->assertStringContainsString('Comment', $comment->getContent()->toString());
    }
  }
}
