<?php

namespace App\Domain\ValueObjects;

class PolicyName
{
  private string $name;

  public function __construct(string $name)
  {
    $this->name = $name;
  }

  public function toString(): string
  {
    return $this->name;
  }
}
