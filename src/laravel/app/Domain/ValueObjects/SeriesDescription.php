<?php

namespace App\Domain\ValueObjects;

class SeriesDescription
{
  private string $description;
  private const MAX_LENGTH = 500; // シリーズ説明として適切な最大文字数
  private const MIN_LENGTH = 0;   // 最小文字数（空の説明を許可）

  public function __construct(string $description)
  {
    $this->validate($description);
    $this->description = $description;
  }

  private function validate(string $description): void
  {
    $length = mb_strlen($description);

    if ($length < self::MIN_LENGTH) {
      throw new \InvalidArgumentException('シリーズ説明は空文字列か、それ以上である必要があります。');
    }

    if ($length > self::MAX_LENGTH) {
      throw new \InvalidArgumentException("シリーズ説明は" . self::MAX_LENGTH . "文字以下である必要があります。");
    }
  }

  public function toString(): string
  {
    return $this->description;
  }

  public function isEmpty(): bool
  {
    return $this->description === '';
  }
}
