<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Hash;

class Password
{
  private string $password;

  public function __construct(string $password)
  {
    $this->password = Hash::needsRehash($password) ? Hash::make($password) : $password;
  }
  public function toString(): string
  {
    return $this->password;
  }

  public function verify(string $password): bool
  {
    return Hash::check($password, $this->password);
  }
}
