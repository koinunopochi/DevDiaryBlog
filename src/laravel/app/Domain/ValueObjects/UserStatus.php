<?php

namespace App\Domain\ValueObjects;

class UserStatus
{
  public const STATUS_ACTIVE = 'Active';
  public const STATUS_INACTIVE = 'Inactive';
  public const STATUS_SUSPENDED = 'Suspended';
  public const STATUS_DELETED = 'Deleted';

  private string $status;

  public function __construct(string $status)
  {
    $this->validate($status);
    $this->status = $status;
  }

  private function validate(string $status): void
  {
    if (!in_array($status, [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_SUSPENDED, self::STATUS_DELETED])) {
      throw new \InvalidArgumentException("statusの値が不正です: {$status}");
    }
  }

  public function toString(): string
  {
    return $this->status;
  }
}
