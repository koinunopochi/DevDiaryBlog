<?php

namespace Database\Factories;

use App\Models\EloquentTag;
use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentTagFactory extends Factory
{
  protected $model = EloquentTag::class;

  public function definition(): array
  {
    return [
      'id' => (new TagId())->toString(),
      'name' => $this->generateValidTagName(),
    ];
  }

  private function generateValidTagName(): string
  {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわをんアイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンー一二三四五六七八九十百千万億兆';
    $length = $this->faker->numberBetween(1, 25);
    $name = '';

    for ($i = 0; $i < $length; $i++) {
      $name .= mb_substr($characters, $this->faker->numberBetween(0, mb_strlen($characters) - 1), 1);
    }

    return (new TagName($name))->toString();
  }
}
