<?php

namespace App\Application\DataTransferObjects;

use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\SocialLinkCollection;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;
use App\Domain\ValueObjects\UserId;
use Illuminate\Http\Request;

class SaveProfileDTO
{
  private UserId $userId;
  private DisplayName $displayName;
  private UserBio $bio;
  private Url $avatarUrl;
  private SocialLinkCollection $socialLinks;

  public function __construct(Request $request, string $userId)
  {
    $this->userId = new UserId($userId);
    $this->displayName = new DisplayName($request->input('displayName'));
    $this->bio = new UserBio($request->input('bio'));
    $this->avatarUrl = new Url($request->input('avatarUrl'));
    $this->socialLinks = new SocialLinkCollection($request->input('socialLinks'));
  }

  public function toProfile(): Profile
  {
    return new Profile(
      $this->userId,
      $this->displayName,
      $this->bio,
      $this->avatarUrl,
      $this->socialLinks
    );
  }
}
