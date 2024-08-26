<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\ValueObjects\RoleDescription;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use Tests\TestCase;
use App\Domain\Entities\Role;

class RoleTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $roleId = new RoleId();
    $roleName = new RoleName('テストロール');
    $roleDescription = new RoleDescription('テスト用の説明');

    // When
    $role = new Role($roleId, $roleName, $roleDescription);

    // Then
    $this->assertInstanceOf(Role::class, $role);
    $this->assertEquals($roleId, $role->getId());
    $this->assertEquals($roleName, $role->getName());
    $this->assertEquals($roleDescription, $role->getDescription());
  }
}
