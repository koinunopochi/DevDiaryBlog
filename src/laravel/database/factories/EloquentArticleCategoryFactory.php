<?php

namespace Database\Factories;

use App\Models\EloquentArticleCategory;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleCategoryName;
use App\Domain\ValueObjects\ArticleCategoryDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentArticleCategoryFactory extends Factory
{
  protected $model = EloquentArticleCategory::class;

  public function definition(): array
  {
    return [
      'id' => (new ArticleCategoryId())->toString(),
      'name' => $this->generateValidArticleCategoryName(),
      'description' => $this->generateValidArticleCategoryDescription(),
    ];
  }

  private function generateValidArticleCategoryName(): string
  {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわをんアイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンー一二三四五六七八九十百千万億兆';
    $length = $this->faker->numberBetween(1, 50);
    $name = '';

    for ($i = 0; $i < $length; $i++) {
      $name .= mb_substr($characters, $this->faker->numberBetween(0, mb_strlen($characters) - 1), 1);
    }

    return (new ArticleCategoryName($name))->toString();
  }

  private function generateValidArticleCategoryDescription(): string
  {
    $description = $this->faker->sentence($this->faker->numberBetween(1, 10));
    return (new ArticleCategoryDescription($description))->toString();
  }
}
