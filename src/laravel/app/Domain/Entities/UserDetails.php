<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\UserId;

class UserDetails
{
  private User $user;
  private ?Profile $profile;

  public function __construct(User $user, ?Profile $profile = null)
  {
    $this->user = $user;
    $this->profile = $profile;
  }

  public function toArray(): array
  {
    $userArray = $this->user->toArray();
    unset($userArray['id']); // idは重複するため削除
    
    $profileArray = $this->profile ? $this->profile->toArray() : null;
    if ($profileArray) {
      unset($profileArray['id']); // idは重複するため削除
    }

    return [
      'id' => $this->user->getUserId()->toString(),
      'user' => $userArray,
      'profile' => $profileArray,
    ];
  }

  // TODO:ポリシーを作成したタイミングで消す
  /**
   * @deprecated 近いうちに削除される予定（ポリシーを作成したタイミングで消す）
   */
  public function canUpdate(UserId $userId): bool
  {
  return $userId->equals($this->user->getUserId());
  }
}
