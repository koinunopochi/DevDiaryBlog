<?php

namespace Database\Factories;

use App\Models\EloquentProfile;
use App\Models\User as EloquentUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentProfileFactory extends Factory
{
  protected $model = EloquentProfile::class;

  public function definition(): array
  {
    return [
      'user_id' => EloquentUser::factory()->create()->id,
      'display_name' => fake()->name(),
      'bio' => fake()->sentence(),
      'avatar_url' => fake()->imageUrl(),
      'social_links' => json_encode([
        'twitter' => fake()->url(),
        'facebook' => fake()->url(),
      ]),
    ];
  }
}
