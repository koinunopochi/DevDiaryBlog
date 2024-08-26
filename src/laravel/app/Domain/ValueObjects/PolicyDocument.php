<?php

namespace App\Domain\ValueObjects;

class PolicyDocument
{
  private string $policyDocument;

  public function __construct(string $policyDocument)
  {
    $this->policyDocument = $policyDocument;
  }

  public function toString(): string
  {
    return $this->policyDocument;
  }
}
