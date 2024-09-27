<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\TagId;
use Ramsey\Uuid\Uuid;

class TagIdTest extends TestCase
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
    $tagId = 'tag00000-' . substr(Uuid::uuid4()->toString(), 9);

    // When
    $tagIdValueObject = new TagId($tagId);

    // Then
    $this->assertEquals($tagId, $tagIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidTagIdFormat(): void
  {
    // Given
    $tagId = "tag00000-invalid-tag-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new TagId($tagId);
  }

  /**
   * @test
   */
  public function testInvalidTagIdPrefix(): void
  {
    // Given
    $tagId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new TagId($tagId);
  }

  /**
   * @test
   */
  public function testInvalidTagIdUuidVersion(): void
  {
    // Given
    $tagId = 'tag00000-' . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new TagId($tagId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $tagIdValueObject = new TagId();

    // Then
    $this->assertStringStartsWith('tag00000-', $tagIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testCanUpdate(): void
  {
    // Given
    $tagId = new TagId();
    $otherTagId = new TagId();

    // When & Then
    $this->assertTrue($tagId->equals($tagId));
    $this->assertFalse($tagId->equals($otherTagId));
  }
}
