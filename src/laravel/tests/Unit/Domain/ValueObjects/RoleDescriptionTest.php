<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\RoleDescription;

class RoleDescriptionTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $roleDescriptionString = 'テスト用の説明';

    // When
    $roleDescription = new RoleDescription($roleDescriptionString);

    // Then
    $this->assertInstanceOf(RoleDescription::class, $roleDescription);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $roleDescriptionString = 'テスト用の説明';
    $roleDescription = new RoleDescription($roleDescriptionString);

    // When
    $result = $roleDescription->toString();

    // Then
    $this->assertSame($roleDescriptionString, $result);
  }

  /** @test */
  public function testValidate_TooLong()
  {
    // Given
    $roleDescriptionString = str_repeat('a', 256);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new RoleDescription($roleDescriptionString);
  }

  /** @test */
  public function testValidate_MaximumLength()
  {
    // Given
    $roleDescriptionString = str_repeat('a', 255);

    // When
    $roleDescription = new RoleDescription($roleDescriptionString);

    // Then
    $this->assertInstanceOf(RoleDescription::class, $roleDescription);
  }
}
