<?php

namespace App\Domain\ValueObjects;

class DisplayName
{
  private string $displayName;

  /**
   * @param string $displayName
   */
  public function __construct(string $displayName)
  {
    $this->displayName = $displayName;
  }

  public function toString(): string
  {
    return $this->displayName;
  }
}
