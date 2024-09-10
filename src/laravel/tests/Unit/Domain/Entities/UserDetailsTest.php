<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\UserDetails;
use App\Domain\Entities\User;
use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\SocialLinkCollection;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Domain\ValueObjects\UserStatus;
use Tests\TestCase;

class UserDetailsTest extends TestCase
{
  /** @test */
  public function testToArray()
  {
    // Given
    $userId = new UserId();
    $user = new User(
      $userId,
      new Username("hogehoge"),
      new Email("hogehoge@example.com"),
      new Password("1234567890aA*"),
      new UserStatus(UserStatus::STATUS_ACTIVE),
      new DateTime(),
      new DateTime()
    );

    $profile = new Profile(
      $userId,
      new DisplayName("hogehoge"),
      new UserBio("hogehoge"),
      new Url("https://hogehoge.com"),
      new SocialLinkCollection([])
    );

    $userDetails = new UserDetails($user, $profile);

    // When
    $result = $userDetails->toArray();

    // idの重複を削除する
    $userArray = $user->toArray();
    $profileArray = $profile->toArray();
    unset($userArray['id']);
    unset($profileArray['id']);

    // Then
    $this->assertEquals([
      'id' => $userId->toString(),
      'user' => $userArray,
      'profile' => $profileArray,
    ], $result);
  }

  /** @test */
  public function testToArrayWithoutProfile()
  {
    // Given
    $userId = new UserId();
    $user = new User(
      $userId,
      new Username("hogehoge"),
      new Email("hogehoge@example.com"),
      new Password("1234567890aA*"),
      new UserStatus(UserStatus::STATUS_ACTIVE),
      new DateTime(),
      new DateTime()
    );

    $userDetails = new UserDetails($user);

    // When
    $result = $userDetails->toArray();

    // idの重複を削除する
    $userArray = $user->toArray();
    unset($userArray['id']);

    // Then
    $this->assertEquals([
      'id' => $userId->toString(),
      'user' => $userArray,
      'profile' => null,
    ], $result);
  }

  /** @test */
  public function testCanUpdate()
  {
    // Given
    $userId = new UserId();
    $user = new User(
      $userId,
      new Username("hogehoge"),
      new Email("hogehoge@example.com"),
      new Password("1234567890aA*"),
      new UserStatus(UserStatus::STATUS_ACTIVE),
      new DateTime(),
      new DateTime()
    );
    $profile = new Profile(
      $userId,
      new DisplayName("hogehoge"),
      new UserBio("hogehoge"),
      new Url("https://hogehoge.com"),
      new SocialLinkCollection([])
    );
    $userDetails = new UserDetails($user, $profile);

    // When
    $result = $userDetails->canUpdate($userId);

    // Then
    $this->assertTrue($result);
  }
}
