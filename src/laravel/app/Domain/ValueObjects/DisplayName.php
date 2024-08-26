<?php

namespace App\Domain\ValueObjects;

class DisplayName
{
  private string $displayName;

  /**
   * @param string $displayName
   */
  public function __construct(string $displayName)
  {
    $this->validate($displayName);
    $this->displayName = $displayName;
  }

  public function toString(): string
  {
    return $this->displayName;
  }

  private function validate(string $displayName): void
  {
    if (mb_strlen($displayName) < 1 || mb_strlen($displayName) > 50) {
      throw new \InvalidArgumentException('表示名は1文字以上50文字以下で入力してください。');
    }
  }
}
