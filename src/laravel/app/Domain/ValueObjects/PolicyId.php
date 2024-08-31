<?php

namespace App\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class PolicyId
{
  private string $policyId;
  private string $prefix = "policy00";

  public function __construct(?string $policyId = null)
  {
    if (is_null($policyId)) {
      $this->policyId = $this->prefix . substr(Uuid::uuid4()->toString(), 8);
    } else {
      $this->validate($policyId);
      $this->policyId = $policyId;
    }
  }

  public function validate(string $policyId): void
  {
    // policy-で始まっていない場合はエラー
    if (!str_starts_with($policyId, $this->prefix)) {
      throw new \InvalidArgumentException('PolicyIdは' . $this->prefix . 'から始まる必要があります。');
    }

    // uuid v4の形式
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $policyId)) {
      throw new \InvalidArgumentException("PolicyIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
  }

  public function toString(): string
  {
    return $this->policyId;
  }
}
