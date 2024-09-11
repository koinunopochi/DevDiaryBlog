<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Role;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use App\Domain\ValueObjects\PolicyId;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
  /**
   * Get all Roles
   *
   * @return Collection<int, Role>
   */
  public function all(): Collection;

  /**
   * Find a Role by id
   *
   * @param RoleId $id
   * @return Role|null
   */
  public function findById(RoleId $id): ?Role;

  /**
   * Find a Role by name
   *
   * @param RoleName $name
   * @return Role|null
   */
  public function findByName(RoleName $name): ?Role;

  /**
   * Save a Role
   *
   * @param Role $role
   * @return void
   */
  public function save(Role $role): void;

  /**
   * Delete a Role
   *
   * @param Role $role
   * @return void
   */
  public function delete(Role $role): void;

  /**
   * Find Roles by Policy
   *
   * @param PolicyId $policyId
   * @return Collection<int, Role>
   */
  public function findByPolicy(PolicyId $policyId): Collection;

  /**
   * Add a Policy to a Role
   *
   * @param Role $role
   * @param PolicyId $policyId
   * @return void
   */
  public function addPolicy(Role $role, PolicyId $policyId): void;

  /**
   * Remove a Policy from a Role
   *
   * @param Role $role
   * @param PolicyId $policyId
   * @return void
   */
  public function removePolicy(Role $role, PolicyId $policyId): void;
}
