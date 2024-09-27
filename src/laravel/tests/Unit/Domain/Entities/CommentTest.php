<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CommentContent;
use App\Domain\ValueObjects\DateTime;
use Tests\TestCase;

class CommentTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $commentId = new CommentId();
    $articleId = new ArticleId();
    $authorId = new UserId();
    $content = new CommentContent('これはテストコメントです。');
    $createdAt = new DateTime();
    $updatedAt = new DateTime();

    // When
    $comment = new Comment($commentId, $articleId, $authorId, $content, $createdAt, $updatedAt);

    // Then
    $this->assertInstanceOf(Comment::class, $comment);
    $this->assertEquals($commentId, $comment->getId());
    $this->assertEquals($articleId, $comment->getArticleId());
    $this->assertEquals($authorId, $comment->getAuthorId());
    $this->assertEquals($content, $comment->getContent());
    $this->assertEquals($createdAt, $comment->getCreatedAt());
    $this->assertEquals($updatedAt, $comment->getUpdatedAt());
  }

  /** @test */
  public function testUpdateContent()
  {
    // Given
    $originalComment = new Comment(
      $commentId = new CommentId(),
      $articleId = new ArticleId(),
      $authorId = new UserId(),
      new CommentContent('元のコメント'),
      $createdAt = new DateTime(),
      new DateTime()
    );

    $newContent = new CommentContent('更新されたコメント');
    $newUpdatedAt = new DateTime();

    // When
    // When
    $updatedComment = $originalComment->updateContent($newContent, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalComment, $updatedComment);
    $this->assertEquals($newContent, $updatedComment->getContent());
    $this->assertEquals($newUpdatedAt, $updatedComment->getUpdatedAt());

    // 元のコメントが変更されていないことを確認
    $this->assertNotEquals($newContent, $originalComment->getContent());
    $this->assertNotEquals($newUpdatedAt, $originalComment->getUpdatedAt());

    // その他の属性が維持されていることを確認
    $this->assertEquals($commentId, $updatedComment->getId());
    $this->assertEquals($articleId, $updatedComment->getArticleId());
    $this->assertEquals($authorId, $updatedComment->getAuthorId());
    $this->assertEquals($createdAt, $updatedComment->getCreatedAt());
  }
}
