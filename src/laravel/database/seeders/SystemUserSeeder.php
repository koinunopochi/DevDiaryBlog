<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
  const SYSTEM_USER_UUID = 'system00-0000-0000-0000-000000000000';
  const SYSTEM_USER_NAME = 'System';
  const SYSTEM_USER_EMAIL = 'system@example.com';

  public function run()
  {
    $password = $this->generateComplexPassword();

    User::create([
      'id' => self::SYSTEM_USER_UUID,
      'name' => self::SYSTEM_USER_NAME,
      'email' => self::SYSTEM_USER_EMAIL,
      'password' => Hash::make($password),
      'email_verified_at' => now(),
      'remember_token' => null,
    ]);

    $this->command->info('System user created successfully with a complex password.');
    // $this->command->info('Password: ' . $password);
    // $this->command->warn('WARNING: This password is displayed for testing purposes only. In a production environment, never log or display passwords.');
  }

  private function generateComplexPassword(): string
  {
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*()-_=+{};:,<.>';

    $password = '';

    // 各要素を少なくとも1つ含める
    $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
    $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
    $password .= $numbers[rand(0, strlen($numbers) - 1)];
    $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

    // 残りの文字をランダムに生成して60文字にする
    $allChars = $lowercase . $uppercase . $numbers . $specialChars;
    for ($i = 0; $i < 60; $i++) {
      $password .= $allChars[rand(0, strlen($allChars) - 1)];
    }

    // 文字列をシャッフル
    $password = str_shuffle($password);

    // パスワードが要件を満たしているか確認
    if (
      strlen($password) !== 64
      || !preg_match('/[a-z]/', $password)
      || !preg_match('/[A-Z]/', $password)
      || !preg_match('/[0-9]/', $password)
      || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)
    ) {
      // 要件を満たさない場合は再帰的に再生成
      return $this->generateComplexPassword();
    }

    return $password;
  }
}
