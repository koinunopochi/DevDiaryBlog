<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class UserRoleId
{
  private string $userRoleId;
  private string $prefix = 'userRole';
  public function __construct(?string $userRoleId = null)
  {
    if (is_null($userRoleId)) {
      $this->userRoleId = $this->prefix . substr(Uuid::uuid4()->toString(), 8);
    } else {
      $this->validate($userRoleId);
      $this->userRoleId = $userRoleId;
    }
  }

  public function validate(string $userRoleId): void
  {
    // user-role-で始まっていない場合はエラー
    if (!str_starts_with($userRoleId, $this->prefix)) {
      throw new \InvalidArgumentException('UserRoleIdは' . $this->prefix . 'から始まる必要があります。');
    }

    // uuid v4の形式
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $userRoleId)) {
      throw new \InvalidArgumentException('UserRoleIdの形式が正しくありません。' . $this->prefix . 'で始まるUUID v4形式である必要があります。');
    }
  }

  public function toString(): string
  {
    return $this->userRoleId;
  }
}
