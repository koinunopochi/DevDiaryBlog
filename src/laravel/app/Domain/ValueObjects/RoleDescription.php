<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class RoleDescription
{
  private string $roleDescription;

  public function __construct(string $roleDescription)
  {
    $this->roleDescription = $roleDescription;
  }

  public function toString(): string
  {
    return $this->roleDescription;
  }
}
