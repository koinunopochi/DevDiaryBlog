<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class ArticleTagCollection
{
  private array $tagIds;
  private const MAX_TAGS = 5;

  public function __construct(array $tagIds)
  {
    $this->validate($tagIds);
    $this->tagIds = $tagIds;
    Log::info('class : ArticleTagCollection - method : constructor - $tagIds : ' . json_encode($this->tagIds));
  }

  private function validate(array $tagIds): void
  {
    if (count($tagIds) > self::MAX_TAGS) {
      Log::error('class : ArticleTagCollection - method : validate - error : タグの数が上限を超えています');
      throw new \InvalidArgumentException("タグの数が上限の" . self::MAX_TAGS . "を超えています");
    }

    $uniqueTagIds = [];
    foreach ($tagIds as $tagId) {
      if (!$tagId instanceof TagId) {
        Log::error('class : ArticleTagCollection - method : validate - error : 無効なTagIdタイプです');
        throw new \InvalidArgumentException("無効なTagIdタイプです: " . gettype($tagId));
      }

      foreach ($uniqueTagIds as $uniqueTagId) {
        if ($tagId->equals($uniqueTagId)) {
          Log::error('class : ArticleTagCollection - method : validate - error : 重複するTagIdが存在します');
          throw new \InvalidArgumentException("重複するTagIdが存在します");
        }
      }
      $uniqueTagIds[] = $tagId;
    }

    Log::info('class : ArticleTagCollection - method : validate - success');
  }

  public function toArray(): array
  {
    Log::info('class : ArticleTagCollection - method : toArray');
    return $this->tagIds;
  }

  public function count(): int
  {
    return count($this->tagIds);
  }

  public function equals(ArticleTagCollection $other): bool
  {
    if ($this->count() !== $other->count()) {
      return false;
    }

    foreach ($this->tagIds as $tagId) {
      if (!in_array($tagId, $other->toArray(), true)) {
        return false;
      }
    }

    return true;
  }
}
