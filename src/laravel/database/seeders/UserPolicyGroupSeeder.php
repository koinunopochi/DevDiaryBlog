<?php

namespace Database\Seeders;

use App\Domain\ValueObjects\PolicyGroupId;
use Illuminate\Database\Seeder;
use App\Models\EloquentPolicyGroup;
use App\Models\EloquentPolicy;

class UserPolicyGroupSeeder extends Seeder
{
  public function run()
  {
    $policyGroups = [
      [
        'id' => 'policyGp-3522-493e-9967-82ebc40d399c',
        'name' => '一般ユーザー自己管理',
        'description' => '一般ユーザーが自身の情報を管理するためのポリシーグループ',
        'policies' => [
          'policy00-3dcb-4d6b-99cb-f2d91c1f6f08', // ユーザー自己情報読み取り
          'policy00-7d2d-4fdf-9c06-48cc8019acd4', // ユーザー自己情報更新
          'policy00-25e8-441a-814b-38b6db46c79c', // ユーザーのパスワード変更
        ]
      ],
      [
        'id' => 'policyGp-02ec-4281-b82e-49238e75c285',
        'name' => '管理者ユーザー管理',
        'description' => '管理者がユーザー情報を管理するためのポリシーグループ',
        'policies' => [
          'policy00-1648-45eb-9fa5-c367382306fb', // 管理者によるユーザー作成
          'policy00-4da7-4f01-97a0-6bf83ff38461', // 管理者によるユーザー読み取り
          'policy00-e684-4929-b890-2ed0876a5488', // 管理者によるユーザー更新
          'policy00-37bf-46a7-9229-b3156782e177', // 管理者によるユーザー削除
          'policy00-96bc-4cfd-8b50-c013da891f93', // 管理者によるユーザー一覧表示
        ]
      ],
      [
        'id' => 'policyGp-06f9-4a6e-8524-361c74a12291',
        'name' => '公開情報アクセス',
        'description' => '誰でもアクセス可能な公開ユーザー情報に関するポリシーグループ',
        'policies' => [
          'policy00-6584-4d8a-86bb-a954652ec511', // 公開ユーザー情報の読み取り
        ]
      ],
      [
        'id' => 'policyGp-d978-4d86-96a8-5a43303a83aa',
        'name' => 'アクセス制限',
        'description' => 'ユーザーへのアクセスを制限するポリシーグループ',
        'policies' => [
          'policy00-1c1a-406d-8847-e9ab41238f50', // ユーザーアクセス不可（管理者例外あり）
        ]
      ],
    ];

    foreach ($policyGroups as $group) {
      $policyGroup = EloquentPolicyGroup::create([
        'id'=>(new PolicyGroupId($group['id']))->toString(),
        'name' => $group['name'],
        'description' => $group['description'],
      ]);

      $policies = EloquentPolicy::whereIn('id', $group['policies'])->get();
      $policyGroup->policies()->attach($policies);
    }
  }
}
