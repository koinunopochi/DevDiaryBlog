<?php

namespace App\Domain\ValueObjects;

class SeriesTitle
{
  private string $title;
  private const MAX_LENGTH = 100; // シリーズタイトルとして適切な最大文字数
  private const MIN_LENGTH = 1;   // 最小文字数（空のタイトルを防ぐ）

  public function __construct(string $title)
  {
    $this->validate($title);
    $this->title = $title;
  }

  private function validate(string $title): void
  {
    $length = mb_strlen($title);

    if ($length < self::MIN_LENGTH) {
      throw new \InvalidArgumentException('シリーズタイトルは空にできません。');
    }

    if ($length > self::MAX_LENGTH) {
      throw new \InvalidArgumentException("シリーズタイトルは" . self::MAX_LENGTH . "文字以下である必要があります。");
    }
  }

  public function toString(): string
  {
    return $this->title;
  }
}
