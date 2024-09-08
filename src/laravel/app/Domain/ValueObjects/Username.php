<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class Username
{
  private string $value;

  /**
   * @param string $name
   */
  public function __construct(string $name)
  {
    Log::debug('class : Username - method : construct - $name : ' . $name);
    $this->validate($name);
    $this->value = $name;
  }

  private function validate(string $name): void
  {
    $length = mb_strlen($name);
    if ($length < 3 || 20 < $length) {
      throw new \InvalidArgumentException("ユーザー名は3文字以上、20文字以下でなければなりません。");
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
      throw new \InvalidArgumentException("ユーザー名は半角英数字とアンダースコアのみ使用できます。");
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
