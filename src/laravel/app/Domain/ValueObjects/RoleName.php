<?php

namespace App\Domain\ValueObjects;

class RoleName
{
  private string $roleName;

  public function __construct(string $roleName)
  {
    $this->validate($roleName);
    $this->roleName = $roleName;
  }

  private function validate(string $roleName): void
  {
    if (mb_strlen($roleName) < 1 || mb_strlen($roleName) > 50) {
      throw new \InvalidArgumentException('RoleNameは1文字以上50文字以下である必要があります。');
    }
  }

  public function toString(): string
  {
    return $this->roleName;
  }
}
