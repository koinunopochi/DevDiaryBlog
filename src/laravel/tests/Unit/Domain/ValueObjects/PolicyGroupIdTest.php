<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyGroupId;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class PolicyGroupIdTest extends TestCase
{
  const CORRECT_PREFIX = "policyGp";
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
    $policyGroupId = self::CORRECT_PREFIX . substr(Uuid::uuid4()->toString(), 8);

    // When
    $policyGroupIdValueObject = new PolicyGroupId($policyGroupId);

    // Then
    $this->assertEquals($policyGroupId, $policyGroupIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidPolicyGroupIdFormat(): void
  {
    // Given
    $policyGroupId = "user0000-invalid-user-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyGroupId($policyGroupId);
  }

  /**
   * @test
   */
  public function testInvalidPolicyGroupIdPrefix(): void
  {
    // Given
    $policyGroupId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyGroupId($policyGroupId);
  }

  /**
   * @test
   */
  public function testInvalidPolicyGroupIdUuidVersion(): void
  {
    // Given
    $policyGroupId = self::CORRECT_PREFIX . Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyGroupId($policyGroupId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $policyGroupIdValueObject = new PolicyGroupId();

    // Then
    $this->assertStringStartsWith(self::CORRECT_PREFIX, $policyGroupIdValueObject->toString());
  }


  /**
   * @test
   */
  public function testCanUpdate(): void
  {
    // Given
    $policyGroupId = new PolicyGroupId();
    $otherPolicyGroupId = new PolicyGroupId();

    // When & Then
    $this->assertTrue($policyGroupId->equals($policyGroupId));
    $this->assertFalse($policyGroupId->equals($otherPolicyGroupId));
  }
}
