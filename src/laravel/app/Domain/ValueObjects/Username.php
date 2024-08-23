<?php

namespace App\Domain\ValueObjects;

class Username
{
  private string $value;

  /**
   * @param string $name
   */
  public function __construct(string $name)
  {
    $this->validate($name);
    $this->value = $name;
  }

  private function validate(string $name): void
  {
    $length = mb_strlen($name);
    if ($length < 3 || 20 < $length) {
      throw new \InvalidArgumentException("ユーザー名は3文字以上、20文字以下でなければなりません。");
    }
  }

  /**
   * @return string
   */
  public function toString(): string
  {
    return $this->value;
  }
}
