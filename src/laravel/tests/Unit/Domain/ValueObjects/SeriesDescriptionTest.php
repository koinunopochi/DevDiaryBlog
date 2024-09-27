<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\SeriesDescription;
use Tests\TestCase;

class SeriesDescriptionTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $description = 'This is a valid series description.';

    // When
    $seriesDescriptionObject = new SeriesDescription($description);

    // Then
    $this->assertInstanceOf(SeriesDescription::class, $seriesDescriptionObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $description = 'This is a valid series description.';

    // When
    $seriesDescriptionObject = new SeriesDescription($description);

    // Then
    $this->assertEquals($description, $seriesDescriptionObject->toString());
  }

  /**
   * @test
   */
  public function testMinimumValidLength(): void
  {
    // Given
    $description = ''; // 最小の有効な長さ（0文字）

    // When
    $seriesDescriptionObject = new SeriesDescription($description);

    // Then
    $this->assertInstanceOf(SeriesDescription::class, $seriesDescriptionObject);
    $this->assertTrue($seriesDescriptionObject->isEmpty());
  }

  /**
   * @test
   */
  public function testMaximumValidLength(): void
  {
    // Given
    $description = str_repeat('あ', 500); // 最大の有効な長さ

    // When
    $seriesDescriptionObject = new SeriesDescription($description);

    // Then
    $this->assertInstanceOf(SeriesDescription::class, $seriesDescriptionObject);
  }

  /**
   * @test
   */
  public function testExceedMaxLength(): void
  {
    // Given
    $description = str_repeat('a', 501); // 最大長を1文字超過

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('シリーズ説明は500文字以下である必要があります。');
    new SeriesDescription($description);
  }

  /**
   * @test
   */
  public function testNonEmptyDescription(): void
  {
    // Given
    $description = 'Non-empty description';

    // When
    $seriesDescriptionObject = new SeriesDescription($description);

    // Then
    $this->assertFalse($seriesDescriptionObject->isEmpty());
  }
}
