<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class ArticleCategoryDescription
{
  private string $description;

  public function __construct(string $description)
  {
    $this->validate($description);
    $this->description = $description;
    Log::info('class : ArticleCategoryDescription - method : constructor - $description : ' . $this->description);
  }

  private function validate(string $description): void
  {
    if (mb_strlen($description) > 255) {
      Log::error('class : ArticleCategoryDescription - method : validate - $description : ' . $description);
      throw new \InvalidArgumentException('カテゴリの説明は255文字以下である必要があります。');
    }
    Log::info('class : ArticleCategoryDescription - method : validate - $description : ' . $description);
  }

  public function toString(): string
  {
    Log::info('class : ArticleCategoryDescription - method : toString - $description : ' . $this->description);
    return $this->description;
  }

  public function equals(ArticleCategoryDescription $other): bool
  {
    return $this->description === $other->description;
  }
}
