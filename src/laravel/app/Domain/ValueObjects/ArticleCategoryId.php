<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ArticleCategoryId
{
  private string $articleCategoryId;
  private string $prefix = 'ArtCATId';

  public function __construct(?string $articleCategoryId = null)
  {
    if (is_null($articleCategoryId)) {
      // UUID v4 を生成し、最初の8文字を 'ArtCATId' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->articleCategoryId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($articleCategoryId);
      $this->articleCategoryId = $articleCategoryId;
    }
    Log::info('class : ArticleCategoryId - method : constructor - $articleCategoryId : ' . $this->articleCategoryId);
  }

  public function validate(string $articleCategoryId): void
  {
    // UUID v4 の形式で、最初の8文字が 'ArtCATId' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $articleCategoryId)) {
      Log::error('class : ArticleCategoryId - method : validate - $articleCategoryId : ' . $articleCategoryId);
      throw new \InvalidArgumentException("ArticleCategoryIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : ArticleCategoryId - method : validate - $articleCategoryId : ' . $articleCategoryId);
  }

  public function toString(): string
  {
    Log::info('class : ArticleCategoryId - method : toString - $articleCategoryId : ' . $this->articleCategoryId);
    return $this->articleCategoryId;
  }

  public function equals(ArticleCategoryId $other): bool
  {
    return $other->articleCategoryId === $this->articleCategoryId;
  }
}
