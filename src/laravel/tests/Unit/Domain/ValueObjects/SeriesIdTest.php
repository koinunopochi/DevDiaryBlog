<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\SeriesId;
use Ramsey\Uuid\Uuid;

class SeriesIdTest extends TestCase
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
    $seriesId = 'SerId000-' . substr(Uuid::uuid4()->toString(), 9);

    // When
    $seriesIdValueObject = new SeriesId($seriesId);

    // Then
    $this->assertEquals($seriesId, $seriesIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidSeriesIdFormat(): void
  {
    // Given
    $seriesId = "SerId000-invalid-series-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SeriesId($seriesId);
  }

  /**
   * @test
   */
  public function testInvalidSeriesIdPrefix(): void
  {
    // Given
    $seriesId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SeriesId($seriesId);
  }

  /**
   * @test
   */
  public function testInvalidSeriesIdUuidVersion(): void
  {
    // Given
    $seriesId = 'SerId000-' . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SeriesId($seriesId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $seriesIdValueObject = new SeriesId();

    // Then
    $this->assertStringStartsWith('SerId000-', $seriesIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $seriesId = new SeriesId();
    $otherSeriesId = new SeriesId();

    // When & Then
    $this->assertTrue($seriesId->equals($seriesId));
    $this->assertFalse($seriesId->equals($otherSeriesId));
  }
}
