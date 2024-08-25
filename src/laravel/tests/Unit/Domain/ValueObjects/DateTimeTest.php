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
}
