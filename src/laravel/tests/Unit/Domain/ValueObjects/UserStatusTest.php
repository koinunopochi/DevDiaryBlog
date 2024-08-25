<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\UserStatus;

class UserStatusTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $status = 'Active';

    // When
    $userStatus = new UserStatus($status);

    // Then
    $this->assertInstanceOf(UserStatus::class, $userStatus);
  }

  public function testToString(): void
  {
    // Given
    $status = UserStatus::STATUS_ACTIVE;
    $userStatus = new UserStatus($status);

    // When
    $result = $userStatus->toString();

    // Then
    $this->assertEquals($status, $result);
  }
  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusActive(): void
  {
    // Given
    $status = UserStatus::STATUS_ACTIVE;

    // When
    $userStatus = new UserStatus($status);

    // Then
    $this->assertInstanceOf(UserStatus::class, $userStatus);
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusInactive(): void
  {
    // Given
    $status = UserStatus::STATUS_INACTIVE;

    // When
    $userStatus = new UserStatus($status);

    // Then
    $this->assertInstanceOf(UserStatus::class, $userStatus);
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusSuspended(): void
  {
    // Given
    $status = UserStatus::STATUS_SUSPENDED;

    // When
    $userStatus = new UserStatus($status);

    // Then
    $this->assertInstanceOf(UserStatus::class, $userStatus);
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusDeleted(): void
  {
    // Given
    $status = UserStatus::STATUS_DELETED;

    // When
    $userStatus = new UserStatus($status);

    // Then
    $this->assertInstanceOf(UserStatus::class, $userStatus);
  }

  /**
   * @test
   */
  public function testCreateInstanceWithInvalidStatus(): void
  {
    // Given
    $status = 'InvalidStatus';

    // Then
    $this->expectException(\Exception::class);

    // When
    new UserStatus($status);
  }
}
