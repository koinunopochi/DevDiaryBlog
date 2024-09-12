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
        'id'=> 'policy00-3dcb-4d6b-99cb-f2d91c1f6f08',
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
        'id' => 'policy00-7d2d-4fdf-9c06-48cc8019acd4',
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
        'id' => 'policy00-1648-45eb-9fa5-c367382306fb',
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
        'id' => 'policy00-4da7-4f01-97a0-6bf83ff38461',
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
        'id' => 'policy00-e684-4929-b890-2ed0876a5488',
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
        'id' => 'policy00-37bf-46a7-9229-b3156782e177',
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
        'id' => 'policy00-96bc-4cfd-8b50-c013da891f93',
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
        'id' => 'policy00-25e8-441a-814b-38b6db46c79c',
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
        'id' => 'policy00-6584-4d8a-86bb-a954652ec511',
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
        'id' => 'policy00-1c1a-406d-8847-e9ab41238f50',
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
        'id' => (new PolicyId($policy['id']))->toString(),
        'name' => $policy['name'],
        'description' => $policy['description'],
        'document' => $policy['document'],
      ]);
    }
  }
}
