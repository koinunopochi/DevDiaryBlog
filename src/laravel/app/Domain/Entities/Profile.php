<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\SocialLinkCollection;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;
use App\Domain\ValueObjects\UserId;

class Profile
{
  private UserId $userId;
  private DisplayName $displayName;
  private UserBio $bio;
  private Url $avatarUrl;
  private SocialLinkCollection $socialLinks;

  public function __construct(
    UserId $userId,
    DisplayName $displayName,
    UserBio $bio,
    Url $avatarUrl,
    SocialLinkCollection $socialLinks,
  ) {
    $this->userId = $userId;
    $this->displayName = $displayName;
    $this->bio = $bio;
    $this->avatarUrl = $avatarUrl;
    $this->socialLinks = $socialLinks;
  }

  public function getUserId(): UserId
  {
    return $this->userId;
  }

  public function getDisplayName(): DisplayName
  {
    return $this->displayName;
  }

  public function getBio(): UserBio
  {
    return $this->bio;
  }

  public function getAvatarUrl(): Url
  {
    return $this->avatarUrl;
  }

  public function getSocialLinks(): SocialLinkCollection
  {
    return $this->socialLinks;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->userId->toString(),
      'displayName' => $this->displayName->toString(),
      'bio' => $this->bio->toString(),
      'avatarUrl' => $this->avatarUrl->toString(),
      'socialLinks' => $this->socialLinks->toArray(),
    ];
  }
}
