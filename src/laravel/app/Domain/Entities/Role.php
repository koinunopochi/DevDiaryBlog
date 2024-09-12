<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\PolicyGroupIdCollection;
use App\Domain\ValueObjects\PolicyIdCollection;
use App\Domain\ValueObjects\RoleDescription;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;

class Role
{
  private RoleId $id;
  private RoleName $name;
  private RoleDescription $description;
  private PolicyIdCollection $policies;
  private PolicyGroupIdCollection $policyGroups;

  public function __construct(
    RoleId $id,
    RoleName $name,
    RoleDescription $description,
    PolicyIdCollection $policies,
    PolicyGroupIdCollection $policyGroups
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->policies = $policies;
    $this->policyGroups = $policyGroups;
  }

  public function getId(): RoleId
  {
    return $this->id;
  }

  public function getName(): RoleName
  {
    return $this->name;
  }

  public function getDescription(): RoleDescription
  {
    return $this->description;
  }

  public function getPolicies(): PolicyIdCollection
  {
    return $this->policies;
  }

  public function getPolicyGroups(): PolicyGroupIdCollection
  {
    return $this->policyGroups;
  }
}
