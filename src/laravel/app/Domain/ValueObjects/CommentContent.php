<?php

namespace App\Domain\ValueObjects;

class CommentContent
{
  private string $content;
  private const MAX_LENGTH = 1000; // ブログコメントとして適切な最大文字数
  private const MIN_LENGTH = 1;    // 最小文字数（空のコメントを防ぐ）

  public function __construct(string $content)
  {
    $this->validate($content);
    $this->content = $content;
  }

  private function validate(string $content): void
  {
    $length = mb_strlen($content);

    if ($length < self::MIN_LENGTH) {
      throw new \InvalidArgumentException('コメント内容は空にできません。');
    }

    if ($length > self::MAX_LENGTH) {
      throw new \InvalidArgumentException("コメント内容は" . self::MAX_LENGTH . "文字以下である必要があります。");
    }
  }

  public function toString(): string
  {
    return $this->content;
  }
}
