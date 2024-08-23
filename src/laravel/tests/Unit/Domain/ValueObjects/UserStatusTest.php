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
    $status = 'Active';
    $userStatus = new UserStatus($status);

    // When
    $result = $userStatus->toString();

    // Then
    $this->assertEquals($status, $result);
  }
}
