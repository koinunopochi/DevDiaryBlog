<?php

namespace App\Domain\ValueObjects;

use App\Domain\ValueObjects\Url;

class Ogp
{
  private Url $url;
  private ?Url $imageUrl;
  private ?string $title;

  public function __construct(
    Url $url,
    ?Url $imageUrl,
    ?string $title
  ) {
    $this->url = $url;
    $this->imageUrl = $imageUrl;
    $this->title = $title;
  }

  public function getUrl(): Url
  {
    return $this->url;
  }

  public function getImageUrl(): ?Url
  {
    return $this->imageUrl;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function toArray(): array
  {
    return [
      "url" => $this->url->toString(),
      "imageUrl" => $this->imageUrl?->toString(),
      "title" => $this->title,
    ];
  }
}
