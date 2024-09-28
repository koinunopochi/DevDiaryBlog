<?php

namespace App\Domain\ValueObjects;

class ArticleContent
{
  private string $content;
  private const MAX_LENGTH = 50000; // 記事内容として適切な最大文字数
  private const MIN_LENGTH = 1;     // 最小文字数（空の記事を防ぐ）

  public function __construct(string $content)
  {
    $this->validate($content);
    $this->content = $content;
  }

  private function validate(string $content): void
  {
    $length = mb_strlen($content);

    if ($length < self::MIN_LENGTH) {
      throw new \InvalidArgumentException('記事内容は空にできません。');
    }

    if ($length > self::MAX_LENGTH) {
      throw new \InvalidArgumentException("記事内容は" . self::MAX_LENGTH . "文字以下である必要があります。");
    }
  }

  public function toString(): string
  {
    return $this->content;
  }
}
