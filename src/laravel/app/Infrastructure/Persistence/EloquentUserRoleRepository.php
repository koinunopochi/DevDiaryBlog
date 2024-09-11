<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\UserRole;
use App\Domain\ValueObjects\UserRoleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\RoleId;
use App\Models\EloquentUserRole;
use Illuminate\Support\Collection;
use App\Domain\Repositories\UserRoleRepositoryInterface;
use DateTime;

class EloquentUserRoleRepository implements UserRoleRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentUserRole::all()->map(function (EloquentUserRole $eloquentUserRole) {
      return $this->toDomainEntity($eloquentUserRole);
    });
  }

  public function findById(UserRoleId $id): ?UserRole
  {
    $eloquentUserRole = EloquentUserRole::find($id->toString());
    return $eloquentUserRole ? $this->toDomainEntity($eloquentUserRole) : null;
  }

  public function findByUserId(UserId $userId): Collection
  {
    return EloquentUserRole::where('user_id', $userId->toString())->get()->map(function (EloquentUserRole $eloquentUserRole) {
      return $this->toDomainEntity($eloquentUserRole);
    });
  }

  public function findByRoleId(RoleId $roleId): Collection
  {
    return EloquentUserRole::where('role_id', $roleId->toString())->get()->map(function (EloquentUserRole $eloquentUserRole) {
      return $this->toDomainEntity($eloquentUserRole);
    });
  }

  public function save(UserRole $userRole): void
  {
    $eloquentUserRole = EloquentUserRole::find($userRole->getId()->toString());

    if (!$eloquentUserRole) {
      $eloquentUserRole = new EloquentUserRole();
      $eloquentUserRole->id = $userRole->getId()->toString();
    }

    $eloquentUserRole->user_id = $userRole->getUserId()->toString();
    $eloquentUserRole->role_id = $userRole->getRoleId()->toString();
    $eloquentUserRole->assigned_at = $userRole->getAssignedAt();
    $eloquentUserRole->assigned_by = $userRole->getAssignedBy()->toString();

    $eloquentUserRole->save();
  }

  public function delete(UserRole $userRole): void
  {
    EloquentUserRole::destroy($userRole->getId()->toString());
  }

  public function findByAssignedDateRange(DateTime $startDate, DateTime $endDate): Collection
  {
    return EloquentUserRole::whereBetween('assigned_at', [$startDate, $endDate])->get()->map(function (EloquentUserRole $eloquentUserRole) {
      return $this->toDomainEntity($eloquentUserRole);
    });
  }

  public function findByAssignedBy(UserId $assignedBy): Collection
  {
    return EloquentUserRole::where('assigned_by', $assignedBy->toString())->get()->map(function (EloquentUserRole $eloquentUserRole) {
      return $this->toDomainEntity($eloquentUserRole);
    });
  }

  public function userHasRole(UserId $userId, RoleId $roleId): bool
  {
    return EloquentUserRole::where('user_id', $userId->toString())
      ->where('role_id', $roleId->toString())
      ->exists();
  }

  private function toDomainEntity(EloquentUserRole $eloquentUserRole): UserRole
  {
    return new UserRole(
      new UserRoleId($eloquentUserRole->id),
      new UserId($eloquentUserRole->user_id),
      new RoleId($eloquentUserRole->role_id),
      $eloquentUserRole->assigned_at,
      new UserId($eloquentUserRole->assigned_by)
    );
  }
}
