<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
  /**
   * @test
   */
  public function testValidPassword()
  {
    // Given
    $password = 'password';

    // When
    $passwordObject = new Password($password);

    // Then
    $this->assertInstanceOf(Password::class, $passwordObject);
  }

  /**
   * @test
   */
  public function testToString()
  {
    // Given
    $password = 'password';

    // When
    $passwordObject = new Password($password);

    // Then
    $this->assertEquals($password, $passwordObject->toString());
  }
}
