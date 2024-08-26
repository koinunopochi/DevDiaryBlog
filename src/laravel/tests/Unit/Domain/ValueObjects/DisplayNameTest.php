<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\DisplayName;
use Tests\TestCase;

class DisplayNameTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $displayNameString = 'テストユーザー';

    // When
    $displayName = new DisplayName($displayNameString);

    // Then
    $this->assertInstanceOf(DisplayName::class, $displayName);
  }
}
