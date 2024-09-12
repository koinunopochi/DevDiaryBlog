<?php

namespace Database\Factories;

use App\Models\EloquentPolicyGroup;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyGroupDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentPolicyGroupFactory extends Factory
{
  protected $model = EloquentPolicyGroup::class;

  public function definition(): array
  {
    return [
      'id' => (new PolicyGroupId())->toString(),
      'name' => (new PolicyGroupName($this->faker->unique()->words(3, true)))->toString(),
      'description' => (new PolicyGroupDescription($this->faker->sentence()))->toString(),
    ];
  }

  /**
   * ポリシーグループに指定された数のポリシーを関連付ける
   */
  public function withPolicies(int $count = 1)
  {
    return $this->afterCreating(function (EloquentPolicyGroup $policyGroup) use ($count) {
      $policyGroup->policies()->attach(
        \App\Models\EloquentPolicy::factory()->count($count)->create()
      );
    });
  }

  /**
   * ポリシーグループに指定された数のロールを関連付ける
   */
  public function withRoles(int $count = 1)
  {
    return $this->afterCreating(function (EloquentPolicyGroup $policyGroup) use ($count) {
      $policyGroup->roles()->attach(
        \App\Models\EloquentRole::factory()->count($count)->create()
      );
    });
  }
}
