<?php

namespace App\Domain\ValueObjects;

class SocialLinkCollection
{
  private array $socialLinks;

  public function __construct(array $socialLinks)
  {
    $this->socialLinks = $socialLinks;
  }

  public function toArray(): array
  {
    return $this->socialLinks;
  }
}
