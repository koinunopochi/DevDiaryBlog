<?php

namespace App\Domain\ValueObjects;

class Username
{
  private string $value;

  /**
   * @param string $name
   */
  public function __construct(string $name)
  {
    $this->value = $name;
  }

  /**
   * @return string
   */
  public function toString(): string
  {
    return $this->value;
  }
}

