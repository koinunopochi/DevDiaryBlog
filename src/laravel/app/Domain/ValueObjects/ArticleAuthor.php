<?php

namespace App\Domain\ValueObjects;

class ArticleAuthor
{
  private Username $username;
  private DisplayName $displayName;
  private Url $profileImage;

  public function __construct(Username $username, DisplayName $displayName, Url $profileImage)
  {
    $this->username = $username;
    $this->displayName = $displayName;
    $this->profileImage = $profileImage;
  }

  public function getUsername(): Username
  {
    return $this->username;
  }

  public function getDisplayName(): DisplayName
  {
    return $this->displayName;
  }

  public function getProfileImage(): Url
  {
    return $this->profileImage;
  }

  public function toArray(): array
  {
    return [
      'username' => $this->username->toString(),
      'displayName' => $this->displayName->toString(),
      'profileImage' => $this->profileImage->toString(),
    ];
  }
}
