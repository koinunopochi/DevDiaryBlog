<?php

namespace App\Domain\ValueObjects;

class PolicyIdCollection
{
  private $policyIds = [];
  public function __construct(array $policyIds)
  {
    $this->validate($policyIds);
    $this->policyIds = $policyIds;
  }

  private function validate($policyIds): void
  {
    foreach ($policyIds as $policyId) {
      if (!$policyId instanceof PolicyId) {
        throw new \InvalidArgumentException("PolicyIdCollection は、PolicyIdの配列である必要があります");
      }
    }
  }

  public function toArray(): array
  {
    return $this->policyIds;
  }

  public function add(PolicyId $policyId): self
  {
    $newPolicyIds = $this->policyIds;
    $newPolicyIds[] = $policyId;
    return new self($newPolicyIds);
  }

  public function remove(PolicyId $policyId): self
  {
    $newPolicyIds = array_filter($this->policyIds, fn($id) => !$id->equals($policyId));
    return new self($newPolicyIds);
  }

  public function contains(PolicyId $policyId): bool
  {
    foreach ($this->policyIds as $id) {
      if ($id->equals($policyId)) {
        return true;
      }
    }
    return false;
  }
  
  public function count(): int
  {
    return count($this->policyIds);
  }
}
