<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyGroupDescription;
use Tests\TestCase;

class PolicyGroupDescriptionTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $policyGroupDescription = 'This is a policy description.';

    // When
    $policyGroupDescriptionValueObject = new PolicyGroupDescription($policyGroupDescription);

    // Then
    $this->assertInstanceOf(PolicyGroupDescription::class, $policyGroupDescriptionValueObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $policyGroupDescription = 'This is a policy description.';

    // When
    $policyGroupDescriptionValueObject = new PolicyGroupDescription($policyGroupDescription);

    // Then
    $this->assertEquals($policyGroupDescription, $policyGroupDescriptionValueObject->toString());
  }

  /**
   * @test
   */
  public function testValidLength(): void
  {
    // Given
    $policyGroupDescription = str_repeat('a', 255);

    // When
    $policyGroupDescriptionValueObject = new PolicyGroupDescription($policyGroupDescription);

    // Then
    $this->assertInstanceOf(PolicyGroupDescription::class, $policyGroupDescriptionValueObject);
  }

  /**
   * @test
   */
  public function testInvalidLength(): void
  {
    // Given
    $policyGroupDescription = str_repeat('a', 256);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new PolicyGroupDescription($policyGroupDescription);
  }
}
