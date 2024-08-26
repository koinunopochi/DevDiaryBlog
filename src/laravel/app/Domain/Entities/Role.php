<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\RoleDescription;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;

class Role
{
  private RoleId $id;
  private RoleName $name;
  private RoleDescription $description;

  public function __construct(RoleId $id, RoleName $name, RoleDescription $description)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
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
}
