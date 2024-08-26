<?php

namespace App\Domain\ValueObjects;

class UserBio
{
  private string $userBio;

  public function __construct(string $userBio)
  {
    $this->userBio = $userBio;
  }
}
