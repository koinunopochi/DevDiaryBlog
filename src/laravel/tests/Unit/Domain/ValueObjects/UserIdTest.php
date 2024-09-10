<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\UserId;
use Ramsey\Uuid\Uuid;

class UserIdTest extends TestCase
{
  protected function setUp(): void
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
    $userId = 'user0000-' . substr(Uuid::uuid4()->toString(), 9);

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
    $userId = "user0000-invalid-user-id-format";

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
    $userId = 'user0000-' . Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new UserId($userId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $userIdValueObject = new UserId();

    // Then
    $this->assertStringStartsWith('user0000-', $userIdValueObject->toString());
  }


  /**
   * @test
   */
  public function testCanUpdate(): void
  {
    // Given
    $userId = new UserId();
    $otherUserId = new UserId();

    // When & Then
    $this->assertTrue($userId->equals($userId));
    $this->assertFalse($userId->equals($otherUserId));
  }
}
