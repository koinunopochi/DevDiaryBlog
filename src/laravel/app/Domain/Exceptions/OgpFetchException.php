<?php

namespace App\Domain\Exceptions;

use Exception;

class OgpFetchException extends Exception
{
  private $statusCode;

  public function __construct($message, $statusCode = null, $code = 0, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
    $this->statusCode = $statusCode;
  }

  public function getStatusCode()
  {
    return $this->statusCode;
  }
}
