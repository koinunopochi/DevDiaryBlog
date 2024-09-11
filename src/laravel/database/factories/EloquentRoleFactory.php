<?php

namespace Database\Factories;

use App\Domain\ValueObjects\RoleDescription;
use App\Models\EloquentRole;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use App\Models\EloquentPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentRoleFactory extends Factory
{
  protected $model = EloquentRole::class;

  public function definition(): array
  {
    return [
      'id' => (new RoleId())->toString(),
      'name' => (new RoleName($this->faker->unique()->words(2, true)))->toString(),
      'description' => (new RoleDescription($this->faker->sentence()))->toString(),
    ];
  }

  /**
   * ロールに指定した数のポリシーを関連付ける
   */
  public function withPolicies(int $count = 1)
  {
    return $this->afterCreating(function (EloquentRole $role) use ($count) {
      $policies = EloquentPolicy::factory()->count($count)->create();
      $role->policies()->attach($policies);
    });
  }

  /**
   * ロールに既存のポリシーを関連付ける
   */
  public function withExistingPolicies(array $policyIds)
  {
    return $this->afterCreating(function (EloquentRole $role) use ($policyIds) {
      $role->policies()->attach($policyIds);
    });
  }
}
