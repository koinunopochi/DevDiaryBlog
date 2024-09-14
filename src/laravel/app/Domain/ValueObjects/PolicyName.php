<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class PolicyName
{
  private string $name;

  public function __construct(string $name)
  {
    $this->validate($name);
    $this->name = $name;
  }

  private function validate(string $name): void
  {
    Log::debug('class : PolicyName - method : validate - $name :' . $name);

    if (empty($name)) {
      Log::debug('class : PolicyName - method : validate - message : empty($name) = ' . (empty($name)));
      throw new \InvalidArgumentException('PolicyNameは1文字以上50文字以下である必要があります。');
    }

    if (mb_strlen($name) > 50) {
      Log::debug('class : PolicyName - method : validate - message : mb_strwidth($name) > 50 = ' . (mb_strwidth($name) > 50));
      throw new \InvalidArgumentException('PolicyNameは50文字以下である必要があります。');
    }
  }

  public function toString(): string
  {
    return $this->name;
  }
}
