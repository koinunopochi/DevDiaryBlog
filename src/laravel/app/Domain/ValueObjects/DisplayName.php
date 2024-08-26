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
}
