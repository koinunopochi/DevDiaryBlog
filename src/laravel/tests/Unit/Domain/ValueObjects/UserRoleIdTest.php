<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\UserRoleId;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class UserRoleIdTest extends TestCase
{
  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $userId = "user-role-" . Uuid::uuid4()->toString();

    // When
    $userIdValueObject = new UserRoleId($userId);

    // Then
    $this->assertEquals($userId, $userIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidUserIdFormat(): void
  {
    // Given
    $userId = "user-role-invalid-user-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new UserRoleId($userId);
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
    new UserRoleId($userId);
  }

  /**
   * @test
   */
  public function testInvalidUserIdUuidVersion(): void
  {
    // Given
    $userId = 'user-role-' . Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new UserRoleId($userId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $userIdValueObject = new UserRoleId();

    // Then
    $this->assertStringStartsWith('user-role-', $userIdValueObject->toString());
  }
}
