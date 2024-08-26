<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\UserBio;
use Tests\TestCase;

class UserBioTest extends TestCase
{
  /**
   * @test
   */
  public function testCanBeCreated(): void
  {
    // Given
    $bioString = 'テストユーザーの自己紹介';

    // When
    $userBio = new UserBio($bioString);

    // Then
    $this->assertInstanceOf(UserBio::class, $userBio);
  }
}
