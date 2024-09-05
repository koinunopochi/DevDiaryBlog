<?php

namespace App\Domain\Entities;

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
}
