<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class PolicyGroupId
{
  private string $policyGroupId;
  private string $prefix = 'policyGp';

  public function __construct(?string $policyGroupId = null)
  {
    if (is_null($policyGroupId)) {
      // UUID v4 を生成し、最初の8文字を 'policyGp' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->policyGroupId = $this->prefix . substr($uuid, 8);
    } else {
      $this->validate($policyGroupId);
      $this->policyGroupId = $policyGroupId;
    }
    Log::info('class : PolicyGroupId - method : constructor - $policyGroupId : ' . $this->policyGroupId);
  }

  public function validate(string $policyGroupId): void
  {
    // UUID v4 の形式で、最初の8文字が 'policyGp' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $policyGroupId)) {
      Log::error('class : PolicyGroupId - method : validate - $policyGroupId : ' . $policyGroupId);
      throw new \InvalidArgumentException("policyGroupIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : PolicyGroupId - method : validate - $policyGroupId : ' . $policyGroupId);
  }

  public function toString(): string
  {
    Log::info('class : PolicyGroupId - method : toString - $policyGroupId : ' . $this->policyGroupId);
    return $this->policyGroupId;
  }

  public function equals(policyGroupId $other): bool
  {
    return $other->policyGroupId === $this->policyGroupId;
  }
}
