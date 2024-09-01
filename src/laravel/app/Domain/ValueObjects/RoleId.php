<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class RoleId
{
  private string $roleId;
  private string $prefix = 'role0000';
  public function __construct(?string $roleId = null)
  {
    if (is_null($roleId)) {
      $this->roleId = $this->prefix . substr(Uuid::uuid4()->toString(), 8);
    } else {
      $this->validate($roleId);
      $this->roleId = $roleId;
    }
  }

  public function validate(string $roleId): void
  {
    // role-で始まっていない場合はエラー
    if (!str_starts_with($roleId, $this->prefix)) {
      throw new \InvalidArgumentException('RoleIdは' . $this->prefix . 'から始まる必要があります。');
    }
    // uuid v4の形式
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $roleId)) {
      throw new \InvalidArgumentException('RoleIdは' . $this->prefix . 'から始まるUUID v4の形式である必要があります。');
    }
  }

  public function toString(): string
  {
    return $this->roleId;
  }
}
