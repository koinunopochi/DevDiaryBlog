<?php

namespace App\Domain\ValueObjects;

class SeriesOrder
{
  private int $order;
  private const MAX_VALUE = 499; // 最大値は500未満
  private const MIN_VALUE = 1;   // 最小値は1

  public function __construct(int $order)
  {
    $this->validate($order);
    $this->order = $order;
  }

  private function validate(int $order): void
  {
    if ($order < self::MIN_VALUE) {
      throw new \InvalidArgumentException('シリーズの順序の値は1以上である必要があります。');
    }

    if ($order > self::MAX_VALUE) {
      throw new \InvalidArgumentException("シリーズの順序の値は" . (self::MAX_VALUE + 1) . "未満である必要があります。");
    }
  }

  public function toInt(): int
  {
    return $this->order;
  }

  public function equals(SeriesOrder $other): bool
  {
    return $this->order === $other->toInt();
  }

  public function isLessThan(SeriesOrder $other): bool
  {
    return $this->order < $other->toInt();
  }

  public function isGreaterThan(SeriesOrder $other): bool
  {
    return $this->order > $other->toInt();
  }
}
