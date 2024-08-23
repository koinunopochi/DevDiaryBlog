<?php

namespace App\Domain\ValueObjects;

class Email
{
  private string $email;

  public function __construct(string $email)
  {
    $this->email = $email;
  }

  public function toString(): string
  {
    return $this->email;
  }
}
