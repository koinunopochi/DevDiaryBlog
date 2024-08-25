<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Password;
use Illuminate\Support\Facades\Hash;
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
  public function testHashedPassword()
  {
    // Given
    $plainTextPassword = 'secret';

    // When
    $password = new Password($plainTextPassword);

    // Then
    $this->assertNotEquals($plainTextPassword, $password->toString());
    $this->assertTrue(Hash::check($plainTextPassword, $password->toString()));
  }
}
