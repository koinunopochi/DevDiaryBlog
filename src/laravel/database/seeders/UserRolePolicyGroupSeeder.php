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
  public const ADMIN_USER_ROLE_ID = 'role0000-cf39-44eb-94ad-9e2c31ffeb30';
  public function run()
  {
    $roles = [
      [
        'id' => 'role0000-f8dd-4f7f-8a0c-e3061aa3f027',
        'name' => '一般ユーザー',
        'description' => '通常の利用者権限を持つロール',
        'policyGroups' => [
          'policyGp-3522-493e-9967-82ebc40d399c', // 一般ユーザー自己管理
          'policyGp-06f9-4a6e-8524-361c74a12291', // 公開情報アクセス
        ],
      ],
      [
        'id' => self::ADMIN_USER_ROLE_ID,
        'name' => '管理者',
        'description' => 'システム管理者権限を持つロール',
        'policyGroups' => [
          'policyGp-3522-493e-9967-82ebc40d399c', // 一般ユーザー自己管理
          'policyGp-02ec-4281-b82e-49238e75c285', // 管理者ユーザー管理
          'policyGp-06f9-4a6e-8524-361c74a12291', // 公開情報アクセス
        ],
      ],
      [
        'id' => 'role0000-7c67-43fa-ab72-ad41db433ddc',
        'name' => 'セキュリティ管理者',
        'description' => 'セキュリティ関連の特別な権限を持つロール',
        'policyGroups' => [
          'policyGp-3522-493e-9967-82ebc40d399c', // 一般ユーザー自己管理
          'policyGp-02ec-4281-b82e-49238e75c285', // 管理者ユーザー管理
          'policyGp-06f9-4a6e-8524-361c74a12291', // 公開情報アクセス
          'policyGp-d978-4d86-96a8-5a43303a83aa', // アクセス制限
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
