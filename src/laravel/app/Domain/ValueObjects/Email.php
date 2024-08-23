<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Email
{
  private string $email;

  public function __construct(string $email)
  {
    $this->validate($email);
    $this->email = $email;
  }

  private function validate(string $email): void
  {
    $canParseEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$canParseEmail) {
      throw new InvalidArgumentException('emailの形式が不正です');
    }
  }

  public function toString(): string
  {
    return $this->email;
  }
}
