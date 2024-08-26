<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Url
{
  private string $url;

  public function __construct(string $url)
  {
    $this->validate($url);
    $this->url = $url;
  }

  private function validate(string $url): void
  {
    // note: htpとhttが通ってしまう問題があるが、大きな問題ではないため放置する
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      throw new InvalidArgumentException('URLの形式が正しくありません: ' . $url);
    }
  }

  public function toString(): string
  {
    return $this->url;
  }
}
