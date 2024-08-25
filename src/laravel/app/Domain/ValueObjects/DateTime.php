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
}
