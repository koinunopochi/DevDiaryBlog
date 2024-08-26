<?php

namespace App\Domain\ValueObjects;

class PolicyDescription
{
  private string $description;

  public function __construct(string $description)
  {
    $this->validate($description);
    $this->description = $description;
  }

  private function validate(string $description): void
  {
    if (mb_strlen($description) > 255) {
      throw new \InvalidArgumentException('Policy description は255文字以下である必要があります。');
    }
  }

  public function toString(): string
  {
    return $this->description;
  }
}
