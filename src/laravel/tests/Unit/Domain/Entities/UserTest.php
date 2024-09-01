<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Domain\ValueObjects\UserStatus;
use App\Domain\ValueObjects\DateTime;
use Tests\TestCase;

class UserTest extends TestCase
{
  /**
   * @test
   */
  public function testUserCanBeInstantiated(): void
  {
    // Given
    $userId = new UserId();
    $username = new Username('testuser');
    $emailAddress = new Email('test@example.com');
    $hashedPassword = new Password('password*A1aaa');
    $userStatus = new UserStatus(UserStatus::STATUS_ACTIVE);
    $createdAt = new DateTime();
    $updatedAt = new DateTime();

    // When
    $user = new User(
      $userId,
      $username,
      $emailAddress,
      $hashedPassword,
      $userStatus,
      $createdAt,
      $updatedAt
    );

    // Then
    $this->assertEquals($userId, $user->getUserId());
    $this->assertEquals($username, $user->getUsername());
    $this->assertEquals($emailAddress, $user->getEmail());
    $this->assertEquals($hashedPassword, $user->getPassword());
    $this->assertEquals($userStatus, $user->getStatus());
    $this->assertEquals($createdAt, $user->getCreatedAt());
    $this->assertEquals($updatedAt, $user->getUpdatedAt());
  }
}
