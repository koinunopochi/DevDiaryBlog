<?php

namespace App\Domain\ValueObjects;

class SeriesStatus
{
  public const STATUS_DRAFT = 'Draft';
  public const STATUS_PUBLISHED = 'Published';
  public const STATUS_ARCHIVED = 'Archived';
  public const STATUS_DELETED = 'Deleted';

  private string $status;

  public function __construct(string $status)
  {
    $this->validate($status);
    $this->status = $status;
  }

  private function validate(string $status): void
  {
    if (!in_array($status, [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED, self::STATUS_DELETED])) {
      throw new \InvalidArgumentException("シリーズのステータスが不正です: {$status}");
    }
  }

  public function toString(): string
  {
    return $this->status;
  }

  public function isDraft(): bool
  {
    return $this->status === self::STATUS_DRAFT;
  }

  public function isPublished(): bool
  {
    return $this->status === self::STATUS_PUBLISHED;
  }

  public function isArchived(): bool
  {
    return $this->status === self::STATUS_ARCHIVED;
  }

  public function isDeleted(): bool
  {
    return $this->status === self::STATUS_DELETED;
  }
}
