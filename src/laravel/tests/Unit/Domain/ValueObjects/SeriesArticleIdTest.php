<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\SeriesArticleId;
use Ramsey\Uuid\Uuid;

class SeriesArticleIdTest extends TestCase
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
    $seriesArticleId = 'SerArtId-' . substr(Uuid::uuid4()->toString(), 9);

    // When
    $seriesArticleIdValueObject = new SeriesArticleId($seriesArticleId);

    // Then
    $this->assertEquals($seriesArticleId, $seriesArticleIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidSeriesArticleIdFormat(): void
  {
    // Given
    $seriesArticleId = "SerArtId-invalid-series-article-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SeriesArticleId($seriesArticleId);
  }

  /**
   * @test
   */
  public function testInvalidSeriesArticleIdPrefix(): void
  {
    // Given
    $seriesArticleId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SeriesArticleId($seriesArticleId);
  }

  /**
   * @test
   */
  public function testInvalidSeriesArticleIdUuidVersion(): void
  {
    // Given
    $seriesArticleId = 'SerArtId-' . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SeriesArticleId($seriesArticleId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $seriesArticleIdValueObject = new SeriesArticleId();

    // Then
    $this->assertStringStartsWith('SerArtId-', $seriesArticleIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $seriesArticleId = new SeriesArticleId();
    $otherSeriesArticleId = new SeriesArticleId();

    // When & Then
    $this->assertTrue($seriesArticleId->equals($seriesArticleId));
    $this->assertFalse($seriesArticleId->equals($otherSeriesArticleId));
  }
}
