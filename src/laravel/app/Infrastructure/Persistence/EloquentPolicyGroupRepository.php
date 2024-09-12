<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\PolicyGroup;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupIdCollection;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyGroupDescription;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyIdCollection;
use App\Models\EloquentPolicyGroup;
use App\Models\EloquentPolicy;
use App\Domain\Repositories\PolicyGroupRepositoryInterface;

class EloquentPolicyGroupRepository implements PolicyGroupRepositoryInterface
{
  public function all(): PolicyGroupIdCollection
  {
    $policyGroupIds = EloquentPolicyGroup::all()->map(function (EloquentPolicyGroup $eloquentPolicyGroup) {
      return new PolicyGroupId($eloquentPolicyGroup->id);
    })->toArray();

    return new PolicyGroupIdCollection($policyGroupIds);
  }

  public function findById(PolicyGroupId $id): ?PolicyGroup
  {
    $eloquentPolicyGroup = EloquentPolicyGroup::find($id->toString());
    return $eloquentPolicyGroup ? $this->toDomainEntity($eloquentPolicyGroup) : null;
  }

  public function findByName(PolicyGroupName $name): ?PolicyGroup
  {
    $eloquentPolicyGroup = EloquentPolicyGroup::where('name', $name->toString())->first();
    return $eloquentPolicyGroup ? $this->toDomainEntity($eloquentPolicyGroup) : null;
  }

  public function save(PolicyGroup $policyGroup): void
  {
    $eloquentPolicyGroup = EloquentPolicyGroup::find($policyGroup->getId()->toString());

    if (!$eloquentPolicyGroup) {
      $eloquentPolicyGroup = new EloquentPolicyGroup();
      $eloquentPolicyGroup->id = $policyGroup->getId()->toString();
    }

    $eloquentPolicyGroup->name = $policyGroup->getName()->toString();
    $eloquentPolicyGroup->description = $policyGroup->getDescription()->toString();

    $eloquentPolicyGroup->save();

    // ポリシーの関連付けを更新
    $policyIds = $policyGroup->getPolicies()->toArray();
    $eloquentPolicyGroup->policies()->sync(array_map(function ($policyId) {
      return $policyId->toString();
    }, $policyIds));
  }

  public function delete(PolicyGroup $policyGroup): void
  {
    EloquentPolicyGroup::destroy($policyGroup->getId()->toString());
  }

  public function getPolicies(PolicyGroupId $id): PolicyIdCollection
  {
    $eloquentPolicyGroup = EloquentPolicyGroup::find($id->toString());
    if (!$eloquentPolicyGroup) {
      return new PolicyIdCollection([]);
    }

    $policyIds = $eloquentPolicyGroup->policies->map(function (EloquentPolicy $eloquentPolicy) {
      return new PolicyId($eloquentPolicy->id);
    })->toArray();

    return new PolicyIdCollection($policyIds);
  }

  public function addPolicy(PolicyGroupId $groupId, PolicyId $policyId): void
  {
    $eloquentPolicyGroup = EloquentPolicyGroup::find($groupId->toString());
    $eloquentPolicyGroup->policies()->attach($policyId->toString());
  }

  public function removePolicy(PolicyGroupId $groupId, PolicyId $policyId): void
  {
    $eloquentPolicyGroup = EloquentPolicyGroup::find($groupId->toString());
    $eloquentPolicyGroup->policies()->detach($policyId->toString());
  }

  private function toDomainEntity(EloquentPolicyGroup $eloquentPolicyGroup): PolicyGroup
  {
    $policyIds = $eloquentPolicyGroup->policies->map(function (EloquentPolicy $eloquentPolicy) {
      return new PolicyId($eloquentPolicy->id);
    })->toArray();

    return new PolicyGroup(
      new PolicyGroupId($eloquentPolicyGroup->id),
      new PolicyGroupName($eloquentPolicyGroup->name),
      new PolicyGroupDescription($eloquentPolicyGroup->description),
      new PolicyIdCollection($policyIds)
    );
  }
}
