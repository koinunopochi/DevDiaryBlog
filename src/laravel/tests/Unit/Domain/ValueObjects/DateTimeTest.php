<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\DateTime;
use Tests\TestCase;

class DateTimeTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateFromFormat()
  {
    // Given

    // When
    $dateTime = new DateTime();

    // Then
    $this->assertInstanceOf(DateTime::class, $dateTime);
  }

  /**
   * @test
   */
  public function testToString()
  {
    // Given
    $now = new \DateTimeImmutable();
    $dateTime = new DateTime($now->format('Y-m-d H:i:s'));

    // When
    $result = $dateTime->toString();

    // Then
    $this->assertSame($now->format('Y-m-d\TH:i:sP'), $result);
  }
  /**
   * @test
   * @dataProvider dateFormatsProvider
   */
  public function testCreateFromStringWithVariousFormats(string $dateTimeString)
  {
    // Given
    $expectedDateTime = new \DateTimeImmutable($dateTimeString);

    // When
    $dateTime = new DateTime($dateTimeString);

    // Then
    $this->assertSame($expectedDateTime->format('Y-m-d\TH:i:sP'), $dateTime->toString());
  }

  public static function dateFormatsProvider(): array
  {
    return [
      ['2024-02-10 12:34:56'],
      ['2024/02/10 12:34:56'],
      ['20240210123456'],
    ];
  }
}
