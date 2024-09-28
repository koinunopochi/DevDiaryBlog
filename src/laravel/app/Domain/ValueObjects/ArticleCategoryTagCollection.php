<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class ArticleCategoryTagCollection
{
  private array $tagIds;

  public function __construct(array $tagIds)
  {
    $this->validate($tagIds);
    $this->tagIds = $tagIds;
    Log::info('class : ArticleCategoryTagCollection - method : constructor - $tagIds : ' . json_encode($this->tagIds));
  }

  private function validate(array $tagIds): void
  {
    if (count($tagIds) > 10) {
      Log::error('class : ArticleCategoryTagCollection - method : validate - error : タグの数が上限を超えています');
      throw new \InvalidArgumentException("タグの数が上限の10を超えています");
    }

    $uniqueTagIds = [];
    foreach ($tagIds as $tagId) {
      if (!$tagId instanceof TagId) {
        Log::error('class : ArticleCategoryTagCollection - method : validate - error : 無効なTagIdタイプです');
        throw new \InvalidArgumentException("無効なTagIdタイプです: " . gettype($tagId));
      }

      foreach ($uniqueTagIds as $uniqueTagId) {
        if ($tagId->equals($uniqueTagId)) {
          Log::error('class : ArticleCategoryTagCollection - method : validate - error : 重複するTagIdが存在します');
          throw new \InvalidArgumentException("重複するTagIdが存在します");
        }
      }
      $uniqueTagIds[] = $tagId;
    }

    Log::info('class : ArticleCategoryTagCollection - method : validate - success');
  }

  public function toArray(): array
  {
    Log::info('class : ArticleCategoryTagCollection - method : toArray');
    return $this->tagIds;
  }

  public function count(): int
  {
    return count($this->tagIds);
  }

  public function equals(ArticleCategoryTagCollection $other): bool
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

  public function map(callable $callback): array
  {
    Log::info('class : ArticleCategoryTagCollection - method : map');
    return array_map($callback, $this->tagIds);
  }
}
