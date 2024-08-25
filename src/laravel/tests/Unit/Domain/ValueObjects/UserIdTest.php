<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\UserId;
use Ramsey\Uuid\Uuid;

class UserIdTest extends TestCase
{
  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $userId = "user-" . Uuid::uuid4()->toString();

    // When
    $userIdValueObject = new UserId($userId);

    // Then
    $this->assertEquals($userId, $userIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidUserIdFormat(): void
  {
    // Given
    $userId = "user-invalid-user-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new UserId($userId);
  }

  /**
   * @test
   */
  public function testInvalidUserIdPrefix(): void
  {
    // Given
    $userId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new UserId($userId);
  }

  /**
   * @test
   */
  public function testInvalidUserIdUuidVersion(): void
  {
    // Given
    $userId = 'user-' . Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new UserId($userId);
  }
}
