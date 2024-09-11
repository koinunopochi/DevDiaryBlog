<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\UserRole;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserRoleId;
use App\Domain\ValueObjects\DateTime;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
  public function testCreateUserRole()
  {
    // Given
    $userRoleId = new UserRoleId();
    $userId = new UserId();
    $roleId = new RoleId();
    $assignedAt = new DateTime();
    $assignedBy = new UserId();

    // When
    $userRole = new UserRole(
      $userRoleId,
      $userId,
      $roleId,
      $assignedAt,
      $assignedBy
    );

    // Then
    $this->assertInstanceOf(UserRole::class, $userRole);
    $this->assertEquals($userRoleId, $userRole->getId());
    $this->assertEquals($userId, $userRole->getUserId());
    $this->assertEquals($roleId, $userRole->getRoleId());
    $this->assertEquals($assignedAt, $userRole->getAssignedAt());
    $this->assertEquals($assignedBy, $userRole->getAssignedBy());
  }
}
