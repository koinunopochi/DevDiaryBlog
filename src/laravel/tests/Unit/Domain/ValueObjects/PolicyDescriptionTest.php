<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyDescription;
use Tests\TestCase;

class PolicyDescriptionTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $policyDescription = 'This is a policy description.';

    // When
    $policyDescriptionValueObject = new PolicyDescription($policyDescription);

    // Then
    $this->assertInstanceOf(PolicyDescription::class, $policyDescriptionValueObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $policyDescription = 'This is a policy description.';

    // When
    $policyDescriptionValueObject = new PolicyDescription($policyDescription);

    // Then
    $this->assertEquals($policyDescription, $policyDescriptionValueObject->toString());
  }
}
