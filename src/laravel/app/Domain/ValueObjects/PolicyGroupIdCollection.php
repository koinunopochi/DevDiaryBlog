<?php

namespace App\Domain\ValueObjects;

class PolicyGroupIdCollection
{
  private $policyGroupIds = [];
  public function __construct(array $policyGroupIds)
  {
    $this->validate($policyGroupIds);
    $this->policyGroupIds = $policyGroupIds;
  }

  private function validate($policyGroupIds): void
  {
    foreach ($policyGroupIds as $policyGroupId) {
      if (!$policyGroupId instanceof PolicyGroupId) {
        throw new \InvalidArgumentException("PolicyGroupIdCollection は、PolicyGroupIdの配列である必要があります");
      }
    }
  }

  public function toArray(): array
  {
    return $this->policyGroupIds;
  }

  public function add(PolicyGroupId $policyGroupId): self
  {
    $newPolicyGroupIds = $this->policyGroupIds;
    $newPolicyGroupIds[] = $policyGroupId;
    return new self($newPolicyGroupIds);
  }

  public function remove(PolicyGroupId $policyGroupId): self
  {
    $newPolicyGroupIds = array_filter($this->policyGroupIds, fn($id) => !$id->equals($policyGroupId));
    return new self($newPolicyGroupIds);
  }

  public function contains(PolicyGroupId $policyGroupId): bool
  {
    foreach ($this->policyGroupIds as $id) {
      if ($id->equals($policyGroupId)) {
        return true;
      }
    }
    return false;
  }
  
  public function count(): int
  {
    return count($this->policyGroupIds);
  }
}
