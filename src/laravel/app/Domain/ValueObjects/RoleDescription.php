<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class RoleDescription
{
  private string $roleDescription;

  public function __construct(string $roleDescription)
  {
    $this->validate($roleDescription);
    $this->roleDescription = $roleDescription;
  }

  public function toString(): string
  {
    return $this->roleDescription;
  }

  private function validate(string $roleDescription): void
  {
    if (mb_strlen($roleDescription) > 255) {
      throw new InvalidArgumentException('Roleの説明は255文字以内で入力してください');
    }
  }
}
