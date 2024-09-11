<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Policy;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyName;
use App\Domain\ValueObjects\PolicyDescription;
use App\Domain\ValueObjects\PolicyDocument;
use App\Models\EloquentPolicy;
use Illuminate\Support\Collection;
use App\Domain\Repositories\PolicyRepositoryInterface;

class EloquentPolicyRepository implements PolicyRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentPolicy::all()->map(function (EloquentPolicy $eloquentPolicy) {
      return $this->toDomainEntity($eloquentPolicy);
    });
  }

  public function findById(PolicyId $id): ?Policy
  {
    $eloquentPolicy = EloquentPolicy::find($id->toString());
    return $eloquentPolicy ? $this->toDomainEntity($eloquentPolicy) : null;
  }

  public function findByName(PolicyName $name): ?Policy
  {
    $eloquentPolicy = EloquentPolicy::where('name', $name->toString())->first();
    return $eloquentPolicy ? $this->toDomainEntity($eloquentPolicy) : null;
  }

  public function save(Policy $policy): void
  {
    $eloquentPolicy = EloquentPolicy::find($policy->getId()->toString());

    if (!$eloquentPolicy) {
      $eloquentPolicy = new EloquentPolicy();
      $eloquentPolicy->id = $policy->getId()->toString();
    }

    $eloquentPolicy->name = $policy->getName()->toString();
    $eloquentPolicy->description = $policy->getDescription()->toString();
    $eloquentPolicy->document = $policy->getDocument()->toArray();

    $eloquentPolicy->save();
  }

  public function delete(Policy $policy): void
  {
    EloquentPolicy::destroy($policy->getId()->toString());
  }

  public function getByVersion(string $version): Collection
  {
    return EloquentPolicy::where('document->Version', $version)->get()->map(function (EloquentPolicy $eloquentPolicy) {
      return $this->toDomainEntity($eloquentPolicy);
    });
  }

  private function toDomainEntity(EloquentPolicy $eloquentPolicy): Policy
  {
    return new Policy(
      new PolicyId($eloquentPolicy->id),
      new PolicyName($eloquentPolicy->name),
      new PolicyDescription($eloquentPolicy->description),
      new PolicyDocument($eloquentPolicy->document)
    );
  }
}
