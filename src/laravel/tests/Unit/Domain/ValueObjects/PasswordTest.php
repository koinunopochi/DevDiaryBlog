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
    $password = '!@#$%^&*()_+1aA';

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
    $plainTextPassword = '!@#$%^&*()_+1aA';

    // When
    $password = new Password($plainTextPassword);

    // Then
    $this->assertNotEquals($plainTextPassword, $password->toString());
    $this->assertTrue(Hash::check($plainTextPassword, $password->toString()));
  }

  /**
   * @test
   */
  public function testPreservesHashedPasswords()
  {
    // Given
    $hashedPassword = Hash::make('!@#$%^&*()_+1aA');

    // When
    $password = new Password($hashedPassword);

    // Then
    $this->assertEquals($hashedPassword, $password->toString());
  }

  /**
   * @test
   */
  public function testVerify()
  {
    // Given
    $plainTextPassword = '!@#$%^&*()_+1aA';

    // When
    $password = new Password($plainTextPassword);

    // Then
    $this->assertTrue($password->verify($plainTextPassword));
    $this->assertFalse($password->verify('Password123!'));
  }

  /**
   * @test
   * @dataProvider validPasswords
   */
  public function testValidPasswords(string $password)
  {
    // Given
    // When
    $passwordObject = new Password($password);
    // Then
    $this->assertInstanceOf(Password::class, $passwordObject);
  }

  /**
   * @test
   * @dataProvider invalidPasswords
   */
  public function testInvalidPasswords(string $password)
  {
    // Given
    // When

    // Then
    $this->expectException(\InvalidArgumentException::class);
    new Password($password);
  }

  public static function validPasswords(): array
  {
    return [
      ['Password123!'],
      ['!@#$%^&*()_+1aA'],
      ['1234567890abC+'],
      ['abcdefghijklmnopqrstuvwxyz1A+'],
      ['ABCDEFGHIJKLMNOPQRSTUVWXYZ1a!'],
    ];
  }

  public static function invalidPasswords(): array
  {
    return [
      ['password'],
      ['Password123'],
      ['Password!'],
      ['Password '],
      ['12345678901'],
      ['abcdefghijklmnopqrstuvwxyz'],
      ['ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
      ['!@#$%^&*()_+'],
    ];
  }
}
