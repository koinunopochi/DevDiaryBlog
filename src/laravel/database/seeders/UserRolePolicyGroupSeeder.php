<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EloquentRole;
use App\Models\EloquentPolicyGroup;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use App\Domain\ValueObjects\RoleDescription;

class UserRolePolicyGroupSeeder extends Seeder
{
  public const GENERAL_USER_ROLE_ID = 'role0000-f8dd-4f7f-8a0c-e3061aa3f027';
  public const ADMIN_USER_ROLE_ID = 'role0000-cf39-44eb-94ad-9e2c31ffeb30';
  public const SECURITY_ADMIN_ROLE_ID = 'role0000-7c67-43fa-ab72-ad41db433ddc';

  public function run()
  {
    $roles = [
      [
        'id' => self::GENERAL_USER_ROLE_ID,
        'name' => '一般ユーザー',
        'description' => '通常の利用者権限を持つロール',
        'policyGroups' => [
          UserPolicyGroupSeeder::GENERAL_USER_SELF_MANAGEMENT,
          UserPolicyGroupSeeder::PUBLIC_INFORMATION_ACCESS,
        ],
      ],
      [
        'id' => self::ADMIN_USER_ROLE_ID,
        'name' => '管理者',
        'description' => 'システム管理者権限を持つロール',
        'policyGroups' => [
          UserPolicyGroupSeeder::GENERAL_USER_SELF_MANAGEMENT,
          UserPolicyGroupSeeder::ADMIN_USER_MANAGEMENT,
          UserPolicyGroupSeeder::PUBLIC_INFORMATION_ACCESS,
        ],
      ],
      [
        'id' => self::SECURITY_ADMIN_ROLE_ID,
        'name' => 'セキュリティ管理者',
        'description' => 'セキュリティ関連の特別な権限を持つロール',
        'policyGroups' => [
          UserPolicyGroupSeeder::GENERAL_USER_SELF_MANAGEMENT,
          UserPolicyGroupSeeder::ADMIN_USER_MANAGEMENT,
          UserPolicyGroupSeeder::PUBLIC_INFORMATION_ACCESS,
          UserPolicyGroupSeeder::ACCESS_RESTRICTION,
        ],
      ],
    ];

    foreach ($roles as $roleData) {
      $role = EloquentRole::create([
        'id' => (new RoleId($roleData['id']))->toString(),
        'name' => (new RoleName($roleData['name']))->toString(),
        'description' => (new RoleDescription($roleData['description']))->toString(),
      ]);

      $policyGroups = EloquentPolicyGroup::whereIn('id', $roleData['policyGroups'])->get();
      $role->policyGroups()->attach($policyGroups);
    }
  }
}
