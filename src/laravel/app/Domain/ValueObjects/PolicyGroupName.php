<?php

namespace App\Domain\ValueObjects;

class PolicyGroupName
{
  private string $name;

  public function __construct(string $name)
  {
    $this->validate($name);
    $this->name = $name;
  }

  private function validate(string $name): void
  {
    if (empty($name)) {
      throw new \InvalidArgumentException('PolicyGroupNameは1文字以上50文字以下である必要があります。');
    }

    if (strlen($name) > 50) {
      throw new \InvalidArgumentException('PolicyGroupNameは50文字以下である必要があります。');
    }
  }

  public function toString(): string
  {
    return $this->name;
  }
}
