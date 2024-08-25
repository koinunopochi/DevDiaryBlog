<?php

namespace App\Domain\ValueObjects;

class Password
{
  private string $password;

  public function __construct(string $password)
  {
    $this->password = $password;
  }
}
