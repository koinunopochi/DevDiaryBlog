<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class Likes
{
  private int $value;

  public function __construct(int $value)
  {
    $this->validate($value);
    $this->value = $value;
    Log::info('class : Likes - method : constructor - $value : ' . $this->value);
  }

  private function validate(int $value): void
  {
    if ($value < 0) {
      Log::error('class : Likes - method : validate - error : いいね数は0以上である必要があります');
      throw new InvalidArgumentException('いいね数は0以上である必要があります');
    }
  }

  public function getValue(): int
  {
    return $this->value;
  }

  public function increment(): self
  {
    Log::info('class : Likes - method : increment - before : ' . $this->value);
    return new self($this->value + 1);
  }

  public function decrement(): self
  {
    Log::info('class : Likes - method : decrement - before : ' . $this->value);
    return new self(max(0, $this->value - 1));
  }

  public function equals(self $other): bool
  {
    return $this->value === $other->value;
  }

  public function toString(): string
  {
    return (string) $this->value;
  }
}
