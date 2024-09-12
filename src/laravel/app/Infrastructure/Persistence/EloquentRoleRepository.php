<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Role;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use App\Domain\ValueObjects\RoleDescription;
use App\Domain\ValueObjects\PolicyIdCollection;
use App\Domain\ValueObjects\PolicyId;
use App\Models\EloquentRole;
use Illuminate\Support\Collection;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupIdCollection;

class EloquentRoleRepository implements RoleRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentRole::all()->map(function (EloquentRole $eloquentRole) {
      return $this->toDomainEntity($eloquentRole);
    });
  }

  public function findById(RoleId $id): ?Role
  {
    $eloquentRole = EloquentRole::find($id->toString());
    return $eloquentRole ? $this->toDomainEntity($eloquentRole) : null;
  }

  public function findByName(RoleName $name): ?Role
  {
    $eloquentRole = EloquentRole::where('name', $name->toString())->first();
    return $eloquentRole ? $this->toDomainEntity($eloquentRole) : null;
  }

  public function save(Role $role): void
  {
    $eloquentRole = EloquentRole::find($role->getId()->toString());

    if (!$eloquentRole) {
      $eloquentRole = new EloquentRole();
      $eloquentRole->id = $role->getId()->toString();
    }

    $eloquentRole->name = $role->getName()->toString();
    $eloquentRole->description = $role->getDescription()->toString();

    $eloquentRole->save();

    // ポリシーの関連付けを更新
    $policyIds = $role->getPolicies()->toArray();
    $eloquentRole->policies()->sync($policyIds);
  }

  public function delete(Role $role): void
  {
    EloquentRole::destroy($role->getId()->toString());
  }

  public function findByPolicy(PolicyId $policyId): Collection
  {
    return EloquentRole::whereHas('policies', function ($query) use ($policyId) {
      $query->where('id', $policyId->toString());
    })->get()->map(function (EloquentRole $eloquentRole) {
      return $this->toDomainEntity($eloquentRole);
    });
  }

  public function addPolicy(Role $role, PolicyId $policyId): void
  {
    $eloquentRole = EloquentRole::find($role->getId()->toString());
    $eloquentRole->policies()->attach($policyId->toString());
  }

  public function removePolicy(Role $role, PolicyId $policyId): void
  {
    $eloquentRole = EloquentRole::find($role->getId()->toString());
    $eloquentRole->policies()->detach($policyId->toString());
  }

  private function toDomainEntity(EloquentRole $eloquentRole): Role
  {
    $policyIds = $eloquentRole->policies->pluck('id')->map(function ($id) {
      return new PolicyId($id);
    })->toArray();

    $policyGroupIds = $eloquentRole->policyGroups->pluck('id')->map(function ($id) {
      return new PolicyGroupId($id);
    })->toArray();

    return new Role(
      new RoleId($eloquentRole->id),
      new RoleName($eloquentRole->name),
      new RoleDescription($eloquentRole->description),
      new PolicyIdCollection($policyIds),
      new PolicyGroupIdCollection($policyGroupIds)
    );
  }
}
