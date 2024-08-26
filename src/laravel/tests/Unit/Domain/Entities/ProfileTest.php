<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\SocialLinkCollection;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;
use App\Domain\ValueObjects\UserId;
use Tests\TestCase;

class ProfileTest extends TestCase
{
  /**
   * @test
   */
  public function testProfileCanBeInstantiated(): void
  {
    // Given
    $userId = new UserId();
    $displayName = new DisplayName('testuser');
    $bio = new UserBio('test bio');
    $avatarUrl = new Url('https://example.com/avatar.png');
    $socialLinks = new SocialLinkCollection([]);

    // When
    $profile = new Profile(
      $userId,
      $displayName,
      $bio,
      $avatarUrl,
      $socialLinks
    );

    // Then
    $this->assertEquals($userId, $profile->getUserId());
    $this->assertEquals($displayName, $profile->getDisplayName());
    $this->assertEquals($bio, $profile->getBio());
    $this->assertEquals($avatarUrl, $profile->getAvatarUrl());
    $this->assertEquals($socialLinks, $profile->getSocialLinks());
  }
}
