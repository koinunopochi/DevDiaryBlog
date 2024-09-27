<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\CommentId;
use Ramsey\Uuid\Uuid;

class CommentIdTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $commentId = 'CmtId000' . substr(Uuid::uuid4()->toString(), 8);

    // When
    $commentIdValueObject = new CommentId($commentId);

    // Then
    $this->assertEquals($commentId, $commentIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidCommentIdFormat(): void
  {
    // Given
    $commentId = "CmtId000-invalid-comment-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new CommentId($commentId);
  }

  /**
   * @test
   */
  public function testInvalidCommentIdPrefix(): void
  {
    // Given
    $commentId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new CommentId($commentId);
  }

  /**
   * @test
   */
  public function testInvalidCommentIdUuidVersion(): void
  {
    // Given
    $commentId = 'CmtId000' . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 7);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new CommentId($commentId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $commentIdValueObject = new CommentId();

    // Then
    $this->assertStringStartsWith('CmtId000', $commentIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $commentId = new CommentId();
    $otherCommentId = new CommentId();

    // When & Then
    $this->assertTrue($commentId->equals($commentId));
    $this->assertFalse($commentId->equals($otherCommentId));
  }
}
