<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\SeriesTitle;
use Tests\TestCase;

class SeriesTitleTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $title = 'Valid Series Title';

    // When
    $seriesTitleObject = new SeriesTitle($title);

    // Then
    $this->assertInstanceOf(SeriesTitle::class, $seriesTitleObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $title = 'Valid Series Title';

    // When
    $seriesTitleObject = new SeriesTitle($title);

    // Then
    $this->assertEquals($title, $seriesTitleObject->toString());
  }

  /**
   * @test
   */
  public function testMinimumValidLength(): void
  {
    // Given
    $title = 'a'; // 最小の有効な長さ（1文字）

    // When
    $seriesTitleObject = new SeriesTitle($title);

    // Then
    $this->assertInstanceOf(SeriesTitle::class, $seriesTitleObject);
  }

  /**
   * @test
   */
  public function testMaximumValidLength(): void
  {
    // Given
    $title = str_repeat('あ', 100); // 最大の有効な長さ

    // When
    $seriesTitleObject = new SeriesTitle($title);

    // Then
    $this->assertInstanceOf(SeriesTitle::class, $seriesTitleObject);
  }

  /**
   * @test
   */
  public function testEmptyTitle(): void
  {
    // Given
    $title = '';

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('シリーズタイトルは空にできません。');
    new SeriesTitle($title);
  }

  /**
   * @test
   */
  public function testExceedMaxLength(): void
  {
    // Given
    $title = str_repeat('a', 101); // 最大長を1文字超過

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('シリーズタイトルは100文字以下である必要があります。');
    new SeriesTitle($title);
  }
}
