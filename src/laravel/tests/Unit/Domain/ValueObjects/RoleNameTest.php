<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\RoleName;
use Tests\TestCase;

class RoleNameTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateFromFormat()
  {
    // Given
    $roleNameString = 'テストロール';

    // When
    $roleName = new RoleName($roleNameString);

    // Then
    $this->assertInstanceOf(RoleName::class, $roleName);
  }

  /**
   * @test
   */
  public function testToString()
  {
    // Given
    $roleNameString = 'テストロール';
    $roleName = new RoleName($roleNameString);

    // When
    $result = $roleName->toString();

    // Then
    $this->assertSame($roleNameString, $result);
  }

  /**
   * @test
   * @dataProvider roleNameProvider
   */
  public function testValidate(int $length, bool $isValid)
  {
    // Given
    $roleNameString = str_repeat('a', $length);

    // When
    if (!$isValid) {
      $this->expectException(\InvalidArgumentException::class);
    }

    $roleName = new RoleName($roleNameString);

    // Then
    if ($isValid) {
      $this->assertInstanceOf(RoleName::class, $roleName);
    }
  }

  public static function roleNameProvider(): array
  {
    return [
      '0文字はエラー' => [0, false],
      '1文字は正常' => [1, true],
      '50文字は正常' => [50, true],
      '51文字はエラー' => [51, false],
    ];
  }
}
