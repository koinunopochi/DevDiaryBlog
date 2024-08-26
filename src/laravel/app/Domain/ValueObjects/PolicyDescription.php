<?php

namespace App\Domain\ValueObjects;

class PolicyDescription
{
  private string $description;

  public function __construct(string $description)
  {
    $this->description = $description;
  }

  public function toString(): string
  {
    return $this->description;
  }
}
