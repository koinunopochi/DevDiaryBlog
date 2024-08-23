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
}
