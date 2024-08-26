<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class UserRoleId
{
  private string $userRoleId;

  public function __construct(?string $userRoleId = null)
  {
    if (is_null($userRoleId)) {
      $this->userRoleId = 'user-role-' . Uuid::uuid4()->toString();
    } else {
      $this->validate($userRoleId);
      $this->userRoleId = $userRoleId;
    }
  }

  public function validate(string $userRoleId): void
  {
    // user-role-で始まっていない場合はエラー
    if (!str_starts_with($userRoleId, 'user-role-')) {
      throw new \InvalidArgumentException('UserRoleIdはuser-role-から始まる必要があります。');
    }

    // user-role-を取り外す
    $trimmedUserRoleId = str_replace('user-role-', '', $userRoleId);

    // uuid v4の形式
    $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $trimmedUserRoleId)) {
      throw new \InvalidArgumentException('UUID v4の形式ではありません。');
    }
  }

  public function toString(): string
  {
    return $this->userRoleId;
  }
}
