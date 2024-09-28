<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ArticleId
{
  private string $articleId;
  private string $prefix = 'ArtId000';

  public function __construct(?string $articleId = null)
  {
    if (is_null($articleId)) {
      // UUID v4 を生成し、最初の8文字を 'ArtId000' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->articleId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($articleId);
      $this->articleId = $articleId;
    }
    Log::info('class : ArticleId - method : constructor - $articleId : ' . $this->articleId);
  }

  public function validate(string $articleId): void
  {
    // UUID v4 の形式で、最初の8文字が 'ArtId000' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $articleId)) {
      Log::error('class : ArticleId - method : validate - $articleId : ' . $articleId);
      throw new \InvalidArgumentException("ArticleIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : ArticleId - method : validate - $articleId : ' . $articleId);
  }

  public function toString(): string
  {
    Log::info('class : ArticleId - method : toString - $articleId : ' . $this->articleId);
    return $this->articleId;
  }

  public function equals(ArticleId $other): bool
  {
    return $other->articleId === $this->articleId;
  }
}
