<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleId;
use Ramsey\Uuid\Uuid;

class ArticleIdTest extends TestCase
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
    $articleId = 'ArtId000' . substr(Uuid::uuid4()->toString(), 8);

    // When
    $articleIdValueObject = new ArticleId($articleId);

    // Then
    $this->assertEquals($articleId, $articleIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidArticleIdFormat(): void
  {
    // Given
    $articleId = "ArtId000-invalid-article-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleId($articleId);
  }

  /**
   * @test
   */
  public function testInvalidArticleIdPrefix(): void
  {
    // Given
    $articleId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleId($articleId);
  }

  /**
   * @test
   */
  public function testInvalidArticleIdUuidVersion(): void
  {
    // Given
    $articleId = 'ArtId000' . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleId($articleId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $articleIdValueObject = new ArticleId();

    // Then
    $this->assertStringStartsWith('ArtId000', $articleIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $articleId = new ArticleId();
    $otherArticleId = new ArticleId();

    // When & Then
    $this->assertTrue($articleId->equals($articleId));
    $this->assertFalse($articleId->equals($otherArticleId));
  }
}
