<?php

namespace App\Domain\ValueObjects;

class UserBio
{
  private string $userBio;

  public function __construct(string $userBio)
  {
    $this->userBio = $userBio;
  }

  public function toString(): string
  {
    return $this->userBio;
  }
}
