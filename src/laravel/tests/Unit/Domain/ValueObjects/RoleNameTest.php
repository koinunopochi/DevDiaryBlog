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
}
