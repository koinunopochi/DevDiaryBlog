<?php

namespace App\Domain\ValueObjects;

class UserStatus
{
  private string $status;
  public function __construct(string $status)
  {
    $this->status = $status;
  }
}
