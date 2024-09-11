<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\UserRole;
use App\Domain\ValueObjects\UserRoleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\RoleId;
use Illuminate\Support\Collection;
use App\Domain\ValueObjects\DateTime;

interface UserRoleRepositoryInterface
{
  /**
   * Get all UserRoles
   *
   * @return Collection<int, UserRole>
   */
  public function all(): Collection;

  /**
   * Find a UserRole by id
   *
   * @param UserRoleId $id
   * @return UserRole|null
   */
  public function findById(UserRoleId $id): ?UserRole;

  /**
   * Find UserRoles by UserId
   *
   * @param UserId $userId
   * @return Collection<int, UserRole>
   */
  public function findByUserId(UserId $userId): Collection;

  /**
   * Find UserRoles by RoleId
   *
   * @param RoleId $roleId
   * @return Collection<int, UserRole>
   */
  public function findByRoleId(RoleId $roleId): Collection;

  /**
   * Save a UserRole
   *
   * @param UserRole $userRole
   * @return void
   */
  public function save(UserRole $userRole): void;

  /**
   * Delete a UserRole
   *
   * @param UserRole $userRole
   * @return void
   */
  public function delete(UserRole $userRole): void;

  /**
   * Find UserRoles assigned within a specific date range
   *
   * @param DateTime $startDate
   * @param DateTime $endDate
   * @return Collection<int, UserRole>
   */
  public function findByAssignedDateRange(DateTime $startDate, DateTime $endDate): Collection;

  /**
   * Find UserRoles assigned by a specific user
   *
   * @param UserId $assignedBy
   * @return Collection<int, UserRole>
   */
  public function findByAssignedBy(UserId $assignedBy): Collection;

  /**
   * Check if a user has a specific role
   *
   * @param UserId $userId
   * @param RoleId $roleId
   * @return bool
   */
  public function userHasRole(UserId $userId, RoleId $roleId): bool;
}
