<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Hash;

class Password
{
  private string $password;

  public function __construct(string $password)
  {
    $this->validatePassword($password);
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

  private function validatePassword(string $password): void
  {
    if (strlen($password) < 12) {
      throw new \InvalidArgumentException('Passwordは12文字以上で入力してください');
    }
    if (!preg_match('/[a-z]/', $password)) {
      throw new \InvalidArgumentException('Passwordには小文字が含まれている必要があります');
    }
    if (!preg_match('/[A-Z]/', $password)) {
      throw new \InvalidArgumentException('Passwordには大文字が含まれている必要があります');
    }
    if (!preg_match('/[0-9]/', $password)) {
      throw new \InvalidArgumentException('Passwordには数字が含まれている必要があります');
    }
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
      throw new \InvalidArgumentException('Passwordには記号が含まれている必要があります');
    }
  }
}
