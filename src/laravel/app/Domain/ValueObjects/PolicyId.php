<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class PolicyId
{
  private string $policyId;

  public function __construct(?string $policyId = null)
  {
    if (is_null($policyId)) {
      $this->policyId = 'policy-' . Uuid::uuid4()->toString();
    } else {
      $this->validate($policyId);
      $this->policyId = $policyId;
    }
  }

  public function validate(string $policyId): void
  {
    // policy-で始まっていない場合はエラー
    if (!str_starts_with($policyId, 'policy-')) {
      throw new \InvalidArgumentException('PolicyIdはpolicy-から始まる必要があります。');
    }

    // policy-を取り外す
    $trimmedPolicyId = str_replace('policy-', '', $policyId);

    // uuid v4の形式
    $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $trimmedPolicyId)) {
      throw new \InvalidArgumentException('UUID v4の形式ではありません。');
    }
  }

  public function toString(): string
  {
    return $this->policyId;
  }
}
