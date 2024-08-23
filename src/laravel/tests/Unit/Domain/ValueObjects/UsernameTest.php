<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Username;
use Tests\TestCase;

class UsernameTest extends TestCase
{
  public function testCreateUsername()
  {
    // Given
    $name = "sample_name";

    // When
    $username = new Username($name);

    // Then
    $this->assertEquals($name, $username->toString());
  }

  public function testTooShortName()
  {
    // Given
    $name = "aa";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new Username($name);
  }

  public function testJustMinimumLength()
  {
    // Given
    $name = "aaa";

    // When
    $username = new Username($name);

    // Then
    $this->assertEquals($name, $username->toString());
  }

  public function testJustMaximumLength()
  {
    // Given
    $name = str_repeat("a", 20);

    // When
    $username = new Username($name);

    // Then
    $this->assertEquals($name, $username->toString());
  }

  public function testTooLongName()
  {
    // Given
    $name = str_repeat("a", 21);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new Username($name);
  }
}
