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
}
