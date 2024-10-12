<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\Cursor;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\DateTime;
use InvalidArgumentException;

class CursorTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testCursorCreation(): void
  {
    // Given
    $articleId = new ArticleId();
    $createdAt = new DateTime('2023-01-01T00:00:00Z');
    $updatedAt = new DateTime('2023-01-02T00:00:00Z');

    // When
    $cursor = new Cursor($articleId, $createdAt, $updatedAt);

    // Then
    $this->assertEquals($articleId, $cursor->getId());
    $this->assertEquals($createdAt, $cursor->getCreatedAt());
    $this->assertEquals($updatedAt, $cursor->getUpdatedAt());
  }

  /**
   * @test
   */
  public function testJsonSerialization(): void
  {
    // Given
    $articleId = new ArticleId();
    $createdAt = new DateTime('2023-01-01T00:00:00Z');
    $updatedAt = new DateTime('2023-01-02T00:00:00Z');
    $cursor = new Cursor($articleId, $createdAt, $updatedAt);

    // When
    $json = json_encode($cursor);
    $decodedJson = json_decode($json, true);

    // Then
    $this->assertEquals($articleId->toString(), $decodedJson['id']);
    $this->assertEquals($createdAt->toString(), $decodedJson['createdAt']);
    $this->assertEquals($updatedAt->toString(), $decodedJson['updatedAt']);
  }

  /**
   * @test
   */
  public function testFromJson(): void
  {
    // Given
    $json = json_encode([
      'id' => (new ArticleId())->toString(),
      'createdAt' => (new DateTime('2023-01-01T00:00:00Z'))->toString(),
      'updatedAt' => (new DateTime('2023-01-02T00:00:00Z'))->toString(),
    ]);

    // When
    $cursor = Cursor::fromJson($json);

    // Then
    $this->assertInstanceOf(Cursor::class, $cursor);
    $this->assertEquals((new DateTime('2023-01-01T00:00:00Z'))->toString(), $cursor->getCreatedAt()->toString());
    $this->assertEquals((new DateTime('2023-01-02T00:00:00Z'))->toString(), $cursor->getUpdatedAt()->toString());
  }

  /**
   * @test
   */
  public function testFromBase64(): void
  {
    // Given
    $data = [
      'id' => (new ArticleId())->toString(),
      'createdAt' => (new DateTime('2023-01-01T00:00:00Z'))->toString(),
      'updatedAt' => (new DateTime('2023-01-02T00:00:00Z'))->toString(),
    ];
    $base64 = base64_encode(json_encode($data));

    // When
    $cursor = Cursor::fromBase64($base64);

    // Then
    $this->assertInstanceOf(Cursor::class, $cursor);
    $this->assertEquals($data['id'], $cursor->getId()->toString());
    $this->assertEquals($data['createdAt'], $cursor->getCreatedAt()->toString());
    $this->assertEquals($data['updatedAt'], $cursor->getUpdatedAt()->toString());
  }

  /**
   * @test
   */
  public function testToBase64(): void
  {
    // Given
    $articleId = new ArticleId();
    $createdAt = new DateTime('2023-01-01T00:00:00Z');
    $updatedAt = new DateTime('2023-01-02T00:00:00Z');
    $cursor = new Cursor($articleId, $createdAt, $updatedAt);

    // When
    $base64 = $cursor->toBase64();
    $decodedCursor = Cursor::fromBase64($base64);

    // Then
    $this->assertEquals($cursor->getId(), $decodedCursor->getId());
    $this->assertEquals($cursor->getCreatedAt(), $decodedCursor->getCreatedAt());
    $this->assertEquals($cursor->getUpdatedAt(), $decodedCursor->getUpdatedAt());
  }

  /**
   * @test
   */
  public function testInvalidJson(): void
  {
    // Given
    $invalidJson = '{invalid_json}';

    // When & Then
    $this->expectException(InvalidArgumentException::class);
    Cursor::fromJson($invalidJson);
  }

  /**
   * @test
   */
  public function testInvalidBase64(): void
  {
    // Given
    $invalidBase64 = 'invalid_base64_string';

    // When & Then
    $this->expectException(InvalidArgumentException::class);
    Cursor::fromBase64($invalidBase64);
  }
}
