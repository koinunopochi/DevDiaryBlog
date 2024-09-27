<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class TagId
{
  private string $tagId;
  private string $prefix = 'tag00000';

  public function __construct(?string $tagId = null)
  {
    if (is_null($tagId)) {
      // UUID v4 を生成し、最初の7文字を 'tag00000' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->tagId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($tagId);
      $this->tagId = $tagId;
    }
    Log::info('class : TagId - method : constructor - $tagId : ' . $this->tagId);
  }

  public function validate(string $tagId): void
  {
    // UUID v4 の形式で、最初の8文字が 'tag00000' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $tagId)) {
      Log::error('class : TagId - method : validate - $tagId : ' . $tagId);
      throw new \InvalidArgumentException("TagIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : TagId - method : validate - $tagId : ' . $tagId);
  }

  public function toString(): string
  {
    Log::info('class : TagId - method : toString - $tagId : ' . $this->tagId);
    return $this->tagId;
  }

  public function equals(TagId $other): bool
  {
    return $other->tagId === $this->tagId;
  }
}
