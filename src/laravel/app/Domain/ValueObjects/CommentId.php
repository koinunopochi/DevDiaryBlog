<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class CommentId
{
  private string $commentId;
  private string $prefix = 'CmtId000';

  public function __construct(?string $commentId = null)
  {
    if (is_null($commentId)) {
      // UUID v4 を生成し、最初の8文字を 'CmtId000' で置換
      $uuid = Uuid::uuid4()->toString();
      $this->commentId = $this->prefix . substr($uuid, 7);
    } else {
      $this->validate($commentId);
      $this->commentId = $commentId;
    }
    Log::info('class : CommentId - method : constructor - $commentId : ' . $this->commentId);
  }

  public function validate(string $commentId): void
  {
    // UUID v4 の形式で、最初の8文字が 'CmtId000' であることを確認
    $uuidRegex = '/^' . $this->prefix . '-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    if (!preg_match($uuidRegex, $commentId)) {
      Log::error('class : CommentId - method : validate - $commentId : ' . $commentId);
      throw new \InvalidArgumentException("CommentIdの形式が正しくありません。$this->prefix で始まるUUID v4形式である必要があります。");
    }
    Log::info('class : CommentId - method : validate - $commentId : ' . $commentId);
  }

  public function toString(): string
  {
    Log::info('class : CommentId - method : toString - $commentId : ' . $this->commentId);
    return $this->commentId;
  }

  public function equals(CommentId $other): bool
  {
    return $other->commentId === $this->commentId;
  }
}
