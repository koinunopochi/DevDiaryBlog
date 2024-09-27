<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleCategoryId;
use Ramsey\Uuid\Uuid;

class ArticleCategoryIdTest extends TestCase
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
    $articleCategoryId = 'ArtCATId' . substr(Uuid::uuid4()->toString(), 8);

    // When
    $articleCategoryIdValueObject = new ArticleCategoryId($articleCategoryId);

    // Then
    $this->assertEquals($articleCategoryId, $articleCategoryIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidArticleCategoryIdFormat(): void
  {
    // Given
    $articleCategoryId = "ArtCATId-invalid-category-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryId($articleCategoryId);
  }

  /**
   * @test
   */
  public function testInvalidArticleCategoryIdPrefix(): void
  {
    // Given
    $articleCategoryId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryId($articleCategoryId);
  }

  /**
   * @test
   */
  public function testInvalidArticleCategoryIdUuidVersion(): void
  {
    // Given
    $articleCategoryId = 'ArtCATId' . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryId($articleCategoryId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $articleCategoryIdValueObject = new ArticleCategoryId();

    // Then
    $this->assertStringStartsWith('ArtCATId', $articleCategoryIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $articleCategoryId = new ArticleCategoryId();
    $otherArticleCategoryId = new ArticleCategoryId();

    // When & Then
    $this->assertTrue($articleCategoryId->equals($articleCategoryId));
    $this->assertFalse($articleCategoryId->equals($otherArticleCategoryId));
  }
}
