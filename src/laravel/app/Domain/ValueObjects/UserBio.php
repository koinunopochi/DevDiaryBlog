<?php

namespace App\Domain\ValueObjects;

class UserBio
{
  private string $userBio;

  public function __construct(string $userBio)
  {
    $this->validate($userBio);
    $this->userBio = $userBio;
  }

  public function toString(): string
  {
    return $this->userBio;
  }

  private function validate(string $userBio): void
  {
    if (mb_strlen($userBio) > 500) {
      throw new \InvalidArgumentException('自己紹介は500文字以内で入力してください。');
    }
  }
}
