<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class RoleId
{
  private string $roleId;

  public function __construct(?string $roleId = null)
  {
    if (is_null($roleId)) {
      $this->roleId = 'role-' . Uuid::uuid4()->toString();
    } else {
      $this->validate($roleId);
      $this->roleId = $roleId;
    }
  }

  public function validate(string $roleId): void
  {
    // role-で始まっていない場合はエラー
    if (!str_starts_with($roleId, 'role-')) {
      throw new \InvalidArgumentException('RoleIdはrole-から始まる必要があります。');
    }

    // role-を取り外す
    $trimmedRoleId = str_replace('role-', '', $roleId);

    // uuid v4の形式
    $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $trimmedRoleId)) {
      throw new \InvalidArgumentException('UUID v4の形式ではありません。');
    }
  }

  public function toString(): string
  {
    return $this->roleId;
  }
}
