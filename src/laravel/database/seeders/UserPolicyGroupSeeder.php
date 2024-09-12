<?php

namespace Database\Seeders;

use App\Domain\ValueObjects\PolicyGroupId;
use Illuminate\Database\Seeder;
use App\Models\EloquentPolicyGroup;
use App\Models\EloquentPolicy;

class UserPolicyGroupSeeder extends Seeder
{
  public const GENERAL_USER_SELF_MANAGEMENT = 'policyGp-3522-493e-9967-82ebc40d399c';
  public const ADMIN_USER_MANAGEMENT = 'policyGp-02ec-4281-b82e-49238e75c285';
  public const PUBLIC_INFORMATION_ACCESS = 'policyGp-06f9-4a6e-8524-361c74a12291';
  public const ACCESS_RESTRICTION = 'policyGp-d978-4d86-96a8-5a43303a83aa';

  public function run()
  {
    $policyGroups = [
      [
        'id' => self::GENERAL_USER_SELF_MANAGEMENT,
        'name' => '一般ユーザー自己管理',
        'description' => '一般ユーザーが自身の情報を管理するためのポリシーグループ',
        'policies' => [
          UserPolicySeeder::USER_SELF_READ, // ユーザー自己情報読み取り
          UserPolicySeeder::USER_SELF_UPDATE, // ユーザー自己情報更新
          UserPolicySeeder::USER_CHANGE_PASSWORD, // ユーザーのパスワード変更
        ]
      ],
      [
        'id' => self::ADMIN_USER_MANAGEMENT,
        'name' => '管理者ユーザー管理',
        'description' => '管理者がユーザー情報を管理するためのポリシーグループ',
        'policies' => [
          UserPolicySeeder::ADMIN_CREATE_USER, // 管理者によるユーザー作成
          UserPolicySeeder::ADMIN_READ_USER, // 管理者によるユーザー読み取り
          UserPolicySeeder::ADMIN_UPDATE_USER, // 管理者によるユーザー更新
          UserPolicySeeder::ADMIN_DELETE_USER, // 管理者によるユーザー削除
          UserPolicySeeder::ADMIN_LIST_USERS, // 管理者によるユーザー一覧表示
        ]
      ],
      [
        'id' => self::PUBLIC_INFORMATION_ACCESS,
        'name' => '公開情報アクセス',
        'description' => '誰でもアクセス可能な公開ユーザー情報に関するポリシーグループ',
        'policies' => [
          UserPolicySeeder::READ_PUBLIC_USER_INFO, // 公開ユーザー情報の読み取り
        ]
      ],
      [
        'id' => self::ACCESS_RESTRICTION,
        'name' => 'アクセス制限',
        'description' => 'ユーザーへのアクセスを制限するポリシーグループ',
        'policies' => [
          UserPolicySeeder::DENY_INACCESSIBLE_USER, // ユーザーアクセス不可（管理者例外あり）
        ]
      ],
    ];

    foreach ($policyGroups as $group) {
      $policyGroup = EloquentPolicyGroup::create([
        'id' => (new PolicyGroupId($group['id']))->toString(),
        'name' => $group['name'],
        'description' => $group['description'],
      ]);

      $policies = EloquentPolicy::whereIn('id', $group['policies'])->get();
      $policyGroup->policies()->attach($policies);
    }
  }
}
