<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class ArticleTagNameCollection
{
  private array $tagNames;
  private const MAX_TAGS = 5;

  public function __construct(array $tagNames)
  {
    $this->validate($tagNames);
    $this->tagNames = $tagNames;
    Log::info('class : ArticleTagNameCollection - method : constructor - $tagNames : ' . json_encode($this->tagNames));
  }

  private function validate(array $tagNames): void
  {
    if (count($tagNames) > self::MAX_TAGS) {
      Log::error('class : ArticleTagNameCollection - method : validate - error : タグの数が上限を超えています');
      throw new \InvalidArgumentException("タグの数が上限の" . self::MAX_TAGS . "を超えています");
    }

    $uniqueTagNames = [];
    foreach ($tagNames as $tagName) {
      if (!$tagName instanceof TagName) {
        Log::error('class : ArticleTagNameCollection - method : validate - error : 無効なTagNameタイプです');
        throw new \InvalidArgumentException("無効なTagNameタイプです: " . gettype($tagName));
      }

      foreach ($uniqueTagNames as $uniqueTagName) {
        if ($tagName->equals($uniqueTagName)) {
          Log::error('class : ArticleTagNameCollection - method : validate - error : 重複するTagNameが存在します');
          throw new \InvalidArgumentException("重複するTagNameが存在します");
        }
      }
      $uniqueTagNames[] = $tagName;
    }

    Log::info('class : ArticleTagNameCollection - method : validate - success');
  }

  public function toArray(): array
  {
    Log::info('class : ArticleTagNameCollection - method : toArray');
    return $this->tagNames;
  }

  public function count(): int
  {
    return count($this->tagNames);
  }

  public function equals(ArticleTagNameCollection $other): bool
  {
    if ($this->count() !== $other->count()) {
      return false;
    }

    foreach ($this->tagNames as $tagName) {
      if (!in_array($tagName, $other->toArray(), true)) {
        return false;
      }
    }

    return true;
  }

  public function map(callable $callback): array
  {
    Log::info('class : ArticleTagNameCollection - method : map');
    return array_map($callback, $this->tagNames);
  }
}
