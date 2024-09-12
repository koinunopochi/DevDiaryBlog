<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\ValueObjects\PolicyDescription;
use App\Domain\ValueObjects\PolicyDocument;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyName;
use App\Domain\Entities\Policy;
use Tests\TestCase;

class PolicyTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $policyId = new PolicyId();
    $policyName = new PolicyName('テストポリシー名');
    $policyDescription = new PolicyDescription('テストポリシーの説明');
    $policyDocument = new PolicyDocument(['Statement' => 'sample']);

    // When
    $policy = new Policy(
      $policyId,
      $policyName,
      $policyDescription,
      $policyDocument
    );

    // Then
    $this->assertInstanceOf(Policy::class, $policy);
    $this->assertEquals($policyId, $policy->getId());
    $this->assertEquals($policyName, $policy->getName());
    $this->assertEquals($policyDescription, $policy->getDescription());
    $this->assertEquals($policyDocument, $policy->getDocument());
  }
}
