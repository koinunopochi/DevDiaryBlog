<?php

namespace App\Domain\ValueObjects;

use DateTimeImmutable;
use Exception;

class DateTime
{
  private DateTimeImmutable $datetime;

  /**
   * @throws Exception
   */
  public function __construct(?string $datetime = 'now')
  {
    $this->datetime = new DateTimeImmutable($datetime);
  }

  private function format(string $format = 'Y-m-d\TH:i:sP'): string
  {
    return $this->datetime->format($format);
  }

  public function toString(): string
  {
    return $this->format();
  }
}
