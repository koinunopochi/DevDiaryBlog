<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\PolicyGroupDescription;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyIdCollection;

class PolicyGroup
{
  private PolicyGroupId $id;
  private PolicyGroupName $name;
  private PolicyGroupDescription $description;
  private PolicyIdCollection $policies;

  public function __construct(
    PolicyGroupId $id,
    PolicyGroupName $name,
    PolicyGroupDescription $description,
    PolicyIdCollection $policies
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->policies = $policies;
  }

  public function getId(): PolicyGroupId
  {
    return $this->id;
  }

  public function getName(): PolicyGroupName
  {
    return $this->name;
  }

  public function getDescription(): PolicyGroupDescription
  {
    return $this->description;
  }

  public function getPolicies(): PolicyIdCollection
  {
    return $this->policies;
  }

  public function addPolicy(PolicyId $policyId): void
  {
    if (!$this->policies->contains($policyId)) {
      $this->policies = $this->policies->add($policyId);
    }
  }

  public function removePolicy(PolicyId $policyId): void
  {
    $this->policies = $this->policies->remove($policyId);
  }
}
