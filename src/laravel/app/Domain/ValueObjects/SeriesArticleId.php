<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class SeriesArticleId
{
  private string $seriesArticleId;
  private string $prefix = 'SerArtId';

  public function __construct(?string $seriesArticleId = null)
  {
    if (is_null($seriesArticleId)) {
      // UUID v4 を生成し、最初の8文字を 'SerArtId' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->seriesArticleId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($seriesArticleId);
      $this->seriesArticleId = $seriesArticleId;
    }
    Log::info('class : SeriesArticleId - method : constructor - $seriesArticleId : ' . $this->seriesArticleId);
  }

  public function validate(string $seriesArticleId): void
  {
    // UUID v4 の形式で、最初の8文字が 'SerArtId' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $seriesArticleId)) {
      Log::error('class : SeriesArticleId - method : validate - $seriesArticleId : ' . $seriesArticleId);
      throw new \InvalidArgumentException("SeriesArticleIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : SeriesArticleId - method : validate - $seriesArticleId : ' . $seriesArticleId);
  }

  public function toString(): string
  {
    Log::info('class : SeriesArticleId - method : toString - $seriesArticleId : ' . $this->seriesArticleId);
    return $this->seriesArticleId;
  }

  public function equals(SeriesArticleId $other): bool
  {
    return $other->seriesArticleId === $this->seriesArticleId;
  }
}
