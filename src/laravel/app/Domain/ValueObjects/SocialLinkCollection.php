<?php

namespace App\Domain\ValueObjects;

class SocialLinkCollection
{
  private array $socialLinks;

  public function __construct(array $socialLinks)
  {
    $this->validate($socialLinks);
    $this->socialLinks = $socialLinks;
  }

  private function validate(array $socialLinks): void
  {
    foreach ($socialLinks as $key => $value) {
      new Url($value); // urlの形式が正しいかチェックする

      if ($key === "") {
        throw new \InvalidArgumentException("keyは空にできません: $key");
      }

      if (strlen($key) > 50) {
        throw new \InvalidArgumentException("keyの長さが50文字を超えています: $key");
      }
    }
  }

  public function toArray(): array
  {
    return $this->socialLinks;
  }
}
