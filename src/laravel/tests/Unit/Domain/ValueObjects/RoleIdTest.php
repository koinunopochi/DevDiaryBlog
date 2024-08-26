<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\RoleId;
use Tests\TestCase;

class RoleIdTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateFromString(): void
  {
    // Given
    $roleId = 'role-' . \Ramsey\Uuid\Uuid::uuid4()->toString();

    // When
    $roleIdValueObject = new RoleId($roleId);

    // Then
    $this->assertEquals($roleId, $roleIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidRoleIdFormat(): void
  {
    // Given
    $roleId = "role-invalid-role-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new RoleId($roleId);
  }

  /**
   * @test
   */
  public function testInvalidRoleIdPrefix(): void
  {
    // Given
    $roleId = \Ramsey\Uuid\Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new RoleId($roleId);
  }

  /**
   * @test
   */
  public function testInvalidRoleIdUuidVersion(): void
  {
    // Given
    $roleId = 'role-' . \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'example.com')->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new RoleId($roleId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $roleIdValueObject = new RoleId();

    // Then
    $this->assertStringStartsWith('role-', $roleIdValueObject->toString());
  }
}
