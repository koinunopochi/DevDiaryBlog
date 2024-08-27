<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class UserId
{
  private string $userId;

  public function __construct(?string $userId = null)
  {
    if (is_null($userId)) {
      $this->userId = 'user-' . Uuid::uuid4()->toString();
    } else {
      $this->validate($userId);
      $this->userId = $userId;
    }
  }

  public function validate(string $userId): void
  {
    // user-で始まっていない場合はエラー
    if (!str_starts_with($userId, 'user-')) {
      throw new \InvalidArgumentException('UserIdはuser-から始まる必要があります。');
    }

    // user-を取り外す
    $trimmedUserId = str_replace('user-', '', $userId);

    // uuid v4の形式
    $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $trimmedUserId)) {
      throw new \InvalidArgumentException('UUID v4の形式ではありません。');
    }
  }

  public function toString(): string
  {
    return $this->userId;
  }

  public function toDb(): string
  {
    return str_replace('user-', '', $this->userId);
  }
}
