<?php

namespace Database\Factories;

use App\Models\EloquentUserRole;
use App\Models\User as EloquentUser;
use App\Models\EloquentRole;
use App\Domain\ValueObjects\UserRoleId;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentUserRoleFactory extends Factory
{
  protected $model = EloquentUserRole::class;

  public function definition(): array
  {
    return [
      'id' => (new UserRoleId())->toString(),
      'user_id' => EloquentUser::factory(),
      'role_id' => EloquentRole::factory(),
      'assigned_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
      'assigned_by' => EloquentUser::factory(),
    ];
  }

  /**
   * 特定のユーザーIDを持つUserRoleを作成
   */
  public function forUser(string $userId): self
  {
    return $this->state(function (array $attributes) use ($userId) {
      return [
        'user_id' => $userId,
      ];
    });
  }

  /**
   * 特定のロールIDを持つUserRoleを作成
   */
  public function forRole(string $roleId): self
  {
    return $this->state(function (array $attributes) use ($roleId) {
      return [
        'role_id' => $roleId,
      ];
    });
  }

  /**
   * 特定の割り当て者IDを持つUserRoleを作成
   */
  public function assignedBy(string $assignedBy): self
  {
    return $this->state(function (array $attributes) use ($assignedBy) {
      return [
        'assigned_by' => $assignedBy,
      ];
    });
  }
}
