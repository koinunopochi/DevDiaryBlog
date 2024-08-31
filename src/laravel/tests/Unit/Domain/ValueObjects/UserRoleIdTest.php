<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\UserRoleId;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class UserRoleIdTest extends TestCase
{
  private string $prefix = 'userRole';

  public function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $userId = $this->prefix . substr(Uuid::uuid4()->toString(), 8);

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
    $userId = $this->prefix . "-invalid-user-id-format";

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
    $userId = $this->prefix . '-' . Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString();

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
    $this->assertStringStartsWith($this->prefix, $userIdValueObject->toString());
  }
}
