<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\PolicyId;
use Ramsey\Uuid\Uuid;

class PolicyIdTest extends TestCase
{
  private $prefix = "policy00";
  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $policyId = $this->prefix . substr(Uuid::uuid4()->toString(), 8);

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
    $policyId = $this->prefix . "invalid-policy-id-format";

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
    $policyId = $this->prefix . substr(Uuid::uuid3(Uuid::NAMESPACE_URL, 'example.com')->toString(), 8);

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
    $this->assertStringStartsWith($this->prefix, $policyIdValueObject->toString());
  }

 /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $policyIdString = $this->prefix . substr(Uuid::uuid4()->toString(), 8);
    $policyId1 = new PolicyId($policyIdString);
    $policyId2 = new PolicyId($policyIdString);
    $policyId3 = new PolicyId();

    // When
    $result1 = $policyId1->equals($policyId2);
    $result2 = $policyId1->equals($policyId3);

    // Then
    $this->assertTrue($result1);
    $this->assertFalse($result2);
  }
}
