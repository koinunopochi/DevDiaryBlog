<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class UserId
{
  private string $userId;
  private string $prefix = 'user0000';

  public function __construct(?string $userId = null)
  {
    if (is_null($userId)) {
      // UUID v4 を生成し、最初の8文字を 'user0000' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->userId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($userId);
      $this->userId = $userId;
    }
    Log::info('class : UserId - method : constructor - $userId : ' . $this->userId);
  }

  public function validate(string $userId): void
  {
    // UUID v4 の形式で、最初の8文字が 'user0000' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $userId)) {
      Log::error('class : UserId - method : validate - $userId : ' . $userId);
      throw new \InvalidArgumentException("UserIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : UserId - method : validate - $userId : ' . $userId);
  }

  public function toString(): string
  {
    Log::info('class : UserId - method : toString - $userId : ' . $this->userId);
    return $this->userId;
  }

  public function equals(UserId $other): bool
  {
    return $other->userId === $this->userId;
  }
}
