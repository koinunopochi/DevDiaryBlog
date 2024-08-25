<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\Email;
use InvalidArgumentException;

class EmailTest extends TestCase
{
  public function testToString()
  {
    // Given
    $email_string = 'test@example.com';

    // When
    $email = new Email($email_string);

    // Then
    $this->assertEquals($email_string, $email->toString());
  }

  public function testInvalidEmailFormat()
  {
    // Given
    $invalidEmail = 'invalid-email';

    // When & Then
    $this->expectException(InvalidArgumentException::class);
    new Email($invalidEmail);
  }
}
