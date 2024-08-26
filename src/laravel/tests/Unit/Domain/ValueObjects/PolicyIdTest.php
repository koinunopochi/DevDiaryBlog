<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\PolicyId;
use Ramsey\Uuid\Uuid;

class PolicyIdTest extends TestCase
{
  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $policyId = "policy-" . Uuid::uuid4()->toString();

    // When
    $policyIdValueObject = new PolicyId($policyId);

    // Then
    $this->assertEquals($policyId, $policyIdValueObject->toString());
  }

  /**
   * @test
   */
  public function testInvalidPolicyIdFormat(): void
  {
    // Given
    $policyId = "policy-invalid-policy-id-format";

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyId($policyId);
  }

  /**
   * @test
   */
  public function testInvalidPolicyIdPrefix(): void
  {
    // Given
    $policyId = Uuid::uuid4()->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyId($policyId);
  }

  /**
   * @test
   */
  public function testInvalidPolicyIdUuidVersion(): void
  {
    // Given
    $policyId = 'policy-' . Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString();

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyId($policyId);
  }

  /**
   * @test
   */
  public function testGenerateNewIdWhenNoArgument(): void
  {
    // Given & When
    $policyIdValueObject = new PolicyId();

    // Then
    $this->assertStringStartsWith('policy-', $policyIdValueObject->toString());
  }
}
