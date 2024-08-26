<?php

namespace App\Domain\ValueObjects;

class Url
{
  private string $url;

  public function __construct(string $url)
  {
    $this->url = $url;
  }
}
