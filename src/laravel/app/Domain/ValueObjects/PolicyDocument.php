<?php

namespace App\Domain\ValueObjects;

class PolicyDocument
{
  private array $policyDocument;

  public function __construct(array $policyDocument)
  {
    $this->policyDocument = $policyDocument;
  }

  public function toArray(): array
  {
    return $this->policyDocument;
  }
}
