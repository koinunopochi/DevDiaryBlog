<?php

namespace Database\Seeders;

use App\Models\EloquentPolicy;
use Illuminate\Database\Seeder;
use App\Domain\ValueObjects\PolicyId;

class UserPolicySeeder extends Seeder
{
  public function run()
  {
    $policies = [
      [
        'name' => 'ユーザー自己情報読み取り',
        'description' => 'ユーザーが自分自身の情報を読み取ることを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowUserSelfRead',
            'Effect' => 'Allow',
            'Action' => ['read_user'],
            'Resource' => ['/users/${user:id}'],
            'Condition' => [
              'StringEquals' => [
                'user:id' => '${resource:owner}'
              ]
            ]
          ]
        ]
      ],
      [
        'name' => 'ユーザー自己情報更新',
        'description' => 'ユーザーが自分自身の情報を更新することを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowUserSelfUpdate',
            'Effect' => 'Allow',
            'Action' => ['update_user'],
            'Resource' => ['/users/${user:id}'],
            'Condition' => [
              'StringEquals' => [
                'user:id' => '${resource:owner}'
              ]
            ]
          ]
        ]
      ],
      [
        'name' => '管理者によるユーザー作成',
        'description' => '管理者がユーザーを作成することを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowAdminCreateUser',
            'Effect' => 'Allow',
            'Action' => ['create_user'],
            'Resource' => ['/users/*'],
            'Condition' => [
              'StringLike' => [
                'user:role' => ['admin', 'root_admin']
              ]
            ]
          ]
        ]
      ],
      [
        'name' => '管理者によるユーザー読み取り',
        'description' => '管理者が任意のユーザーの情報を読み取ることを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowAdminReadUser',
            'Effect' => 'Allow',
            'Action' => ['read_user'],
            'Resource' => ['/users/*'],
            'Condition' => [
              'StringLike' => [
                'user:role' => ['admin', 'root_admin']
              ]
            ]
          ]
        ]
      ],
      [
        'name' => '管理者によるユーザー更新',
        'description' => '管理者が任意のユーザーの情報を更新することを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowAdminUpdateUser',
            'Effect' => 'Allow',
            'Action' => ['update_user'],
            'Resource' => ['/users/*'],
            'Condition' => [
              'StringLike' => [
                'user:role' => ['admin', 'root_admin']
              ]
            ]
          ]
        ]
      ],
      [
        'name' => '管理者によるユーザー削除',
        'description' => '管理者がユーザーを削除することを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowAdminDeleteUser',
            'Effect' => 'Allow',
            'Action' => ['delete_user'],
            'Resource' => ['/users/*'],
            'Condition' => [
              'StringLike' => [
                'user:role' => ['admin', 'root_admin']
              ]
            ]
          ]
        ]
      ],
      [
        'name' => '管理者によるユーザー一覧表示',
        'description' => '管理者がユーザー一覧を表示することを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowAdminListUsers',
            'Effect' => 'Allow',
            'Action' => ['list_users'],
            'Resource' => ['/users/*'],
            'Condition' => [
              'StringLike' => [
                'user:role' => ['admin', 'root_admin']
              ]
            ]
          ]
        ]
      ],
      [
        'name' => 'ユーザーのパスワード変更',
        'description' => 'ユーザーが自分自身のパスワードを変更することを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowUserPasswordChange',
            'Effect' => 'Allow',
            'Action' => ['update_user_password'],
            'Resource' => ['/users/${user:id}'],
            'Condition' => [
              'StringEquals' => [
                'user:id' => '${resource:owner}'
              ]
            ]
          ]
        ]
      ],
      [
        'name' => '公開ユーザー情報の読み取り',
        'description' => '誰でも公開されているユーザー情報を読み取ることを許可します。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'AllowPublicUserRead',
            'Effect' => 'Allow',
            'Action' => ['read_user_public_info'],
            'Resource' => ['/users/*']
          ]
        ]
      ],
      [
        'name' => 'ユーザーアクセス不可（管理者例外あり）',
        'description' => 'このポリシーが適用されたユーザーへのアクセスを拒否します。ただし、root管理者と特定の管理者ロールは例外とします。',
        'document' => [
          'Version' => '2024-08-29',
          'Statement' => [
            'Sid' => 'DenyAccessToInaccessibleUser',
            'Effect' => 'Deny',
            'Action' => ['*'],
            'Resource' => ['/users/${user:id}'],
            'Condition' => [
              'StringEquals' => [
                'user:status' => 'inaccessible'
              ],
              'StringNotLike' => [
                'user:role' => ['root_admin', 'system_admin', 'security_admin']
              ]
            ]
          ]
        ]
      ]
    ];

    foreach ($policies as $policy) {
      EloquentPolicy::create([
        'id' => (new PolicyId())->toString(),
        'name' => $policy['name'],
        'description' => $policy['description'],
        'document' => $policy['document'],
      ]);
    }
  }
}
