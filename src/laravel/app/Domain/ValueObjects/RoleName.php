<?php

namespace App\Domain\ValueObjects;

class RoleName
{
  private string $roleName;

  public function __construct(string $roleName)
  {
    $this->roleName = $roleName;
  }

  public function toString(): string
  {
    return $this->roleName;
  }
}
