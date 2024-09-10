<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\RoleDescription;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use Tests\TestCase;
use App\Domain\Entities\Role;
use App\Domain\ValueObjects\PolicyIdCollection;

class RoleTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $roleId = new RoleId();
    $roleName = new RoleName('テストロール');
    $roleDescription = new RoleDescription('テスト用の説明');
    $policyIdCollection = new PolicyIdCollection([new PolicyId(), new PolicyId()]);

    // When
    $role = new Role($roleId, $roleName, $roleDescription, $policyIdCollection);

    // Then
    $this->assertInstanceOf(Role::class, $role);
    $this->assertEquals($roleId, $role->getId());
    $this->assertEquals($roleName, $role->getName());
    $this->assertEquals($roleDescription, $role->getDescription());
    $this->assertEquals($policyIdCollection, $role->getPolicies());
  }
}
