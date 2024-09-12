<?php

namespace Database\Seeders;

use App\Domain\ValueObjects\UserId;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EloquentRole;
use App\Models\EloquentUserRole;
use Illuminate\Support\Facades\Hash;

class RootAdminSeeder extends Seeder
{
  const USER_NAME = 'RootAdmin';
  const USER_EMAIL = 'root_admin@example.com';

  public function run()
  {
    // RootAdminユーザーの作成
    $password = SystemUserSeeder::generateComplexPassword();
    $userId = new UserId();
    $rootAdmin = User::create([
      'id' => $userId->toString(),
      'name' => self::USER_NAME,
      'email' => self::USER_EMAIL,
      'password' => Hash::make($password),
      'email_verified_at' => now(),
      'remember_token' => null,
    ]);

    // 管理者ロールの取得
    $adminRole = EloquentRole::where('id', UserRolePolicyGroupSeeder::ADMIN_USER_ROLE_ID)->first();

    if (!$adminRole) {
      throw new \Exception('Admin role not found. Make sure UserRolePolicyGroupSeeder has been run.');
    }

    // UserRoleの作成
    EloquentUserRole::factory()
      ->forUser($rootAdmin->id)
      ->forRole($adminRole->id)
      ->assignedBy(SystemUserSeeder::SYSTEM_USER_UUID) // SystemUserが割り当てたとする
      ->create();

    $this->command->info('Root Admin user created successfully with a complex password and assigned admin role.');
    $this->command->info('UserId: ' . $userId->toString());
    $this->command->info('Username: ' . self::USER_NAME);
    $this->command->info('UserEmail: ' . self::USER_EMAIL);
    $this->command->info('Password: ' . $password);
    $this->command->warn('WARNING: This password is displayed. In a production environment, never log or display passwords.');
  }
}
