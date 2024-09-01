<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\RoleId;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RoleIdTest extends TestCase
{
  private string $prefix = 'role0000';
  /**
   * @test
   */
  public function testCreateFromString(): void
  {
    // Given
    $roleId = $this->prefix . substr(Uuid::uuid4()->toString(), 8);

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
    $roleId = $this->prefix . "-invalid-role-id-format";

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
    $roleId = "invalid-role-id-prefix" . substr(Uuid::uuid4()->toString(), 8);

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
    $roleId = $this->prefix . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

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
    $this->assertStringStartsWith($this->prefix, $roleIdValueObject->toString());
  }
}
