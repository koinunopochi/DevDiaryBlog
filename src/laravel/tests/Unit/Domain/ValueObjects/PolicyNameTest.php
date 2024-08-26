<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyName;
use Tests\TestCase;

class PolicyNameTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $policyNameString = 'テストポリシー名';

    // When
    $policyName = new PolicyName($policyNameString);

    // Then
    $this->assertInstanceOf(PolicyName::class, $policyName);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $policyNameString = 'テストポリシー名';
    $policyName = new PolicyName($policyNameString);

    // When
    $result = $policyName->toString();

    // Then
    $this->assertEquals($policyNameString, $result);
  }
}
