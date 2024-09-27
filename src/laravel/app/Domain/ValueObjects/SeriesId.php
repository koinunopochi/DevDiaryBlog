<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class SeriesId
{
  private string $seriesId;
  private string $prefix = 'SerId000';

  public function __construct(?string $seriesId = null)
  {
    if (is_null($seriesId)) {
      // UUID v4 を生成し、最初の8文字を 'SerId000' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->seriesId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($seriesId);
      $this->seriesId = $seriesId;
    }
    Log::info('class : SeriesId - method : constructor - $seriesId : ' . $this->seriesId);
  }

  public function validate(string $seriesId): void
  {
    // UUID v4 の形式で、最初の8文字が 'SerId000' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $seriesId)) {
      Log::error('class : SeriesId - method : validate - $seriesId : ' . $seriesId);
      throw new \InvalidArgumentException("SeriesIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : SeriesId - method : validate - $seriesId : ' . $seriesId);
  }

  public function toString(): string
  {
    Log::info('class : SeriesId - method : toString - $seriesId : ' . $this->seriesId);
    return $this->seriesId;
  }

  public function equals(SeriesId $other): bool
  {
    return $other->seriesId === $this->seriesId;
  }
}
