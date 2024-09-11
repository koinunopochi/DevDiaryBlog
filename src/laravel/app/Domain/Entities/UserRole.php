<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserRoleId;
use App\Domain\ValueObjects\DateTime;

class UserRole
{
  private UserRoleId $id;
  private UserId $userId;
  private RoleId $roleId;
  private DateTime $assignedAt;
  private UserId $assignedBy;

  public function __construct(
    UserRoleId $id,
    UserId $userId,
    RoleId $roleId,
    DateTime $assignedAt,
    UserId $assignedBy
  ) {
    $this->id = $id;
    $this->userId = $userId;
    $this->roleId = $roleId;
    $this->assignedAt = $assignedAt;
    $this->assignedBy = $assignedBy;
  }

  public function getId(): UserRoleId
  {
    return $this->id;
  }

  public function getUserId(): UserId
  {
    return $this->userId;
  }

  public function getRoleId(): RoleId
  {
    return $this->roleId;
  }

  public function getAssignedAt(): DateTime
  {
    return $this->assignedAt;
  }

  public function getAssignedBy(): UserId
  {
    return $this->assignedBy;
  }
}
