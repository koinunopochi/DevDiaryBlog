<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\PolicyGroup;
use App\Domain\ValueObjects\PolicyGroupDescription;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyIdCollection;
use Tests\TestCase;

class PolicyGroupTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $policyGroupId = new PolicyGroupId();
    $policyGroupName = new PolicyGroupName('テストポリシーグループ');
    $policyGroupDescription = new PolicyGroupDescription('簡単な説明');
    $policyId_1 = new PolicyId();
    $policyId_2 = new PolicyId();
    $policies = new PolicyIdCollection([$policyId_1, $policyId_2]);

    // When
    $policyGroup = new PolicyGroup($policyGroupId, $policyGroupName, $policyGroupDescription, $policies);

    // Then
    $this->assertInstanceOf(PolicyGroup::class, $policyGroup);
    $this->assertEquals($policyGroupId, $policyGroup->getId());
    $this->assertEquals($policyGroupName, $policyGroup->getName());
    $this->assertEquals($policies, $policyGroup->getPolicies());
  }

  /** @test */
  public function testAddPolicy()
  {
    // Given
    $policyGroupId = new PolicyGroupId();
    $policyGroupName = new PolicyGroupName('テストポリシーグループ');
    $policyGroupDescription = new PolicyGroupDescription('簡単な説明');
    $policyId_1 = new PolicyId();
    $policyId_2 = new PolicyId();
    $policies = new PolicyIdCollection([$policyId_1]);

    // When
    $policyGroup = new PolicyGroup($policyGroupId, $policyGroupName, $policyGroupDescription, $policies);
    $policyGroup->addPolicy($policyId_2);

    // Then
    $this->assertInstanceOf(PolicyGroup::class, $policyGroup);
    $this->assertEquals($policyGroupId, $policyGroup->getId());
    $this->assertEquals($policyGroupName, $policyGroup->getName());
    $this->assertCount(2, $policyGroup->getPolicies()->toArray());
  }

  /** @test */
  public function testRemovePolicy()
  {
    // Given
    $policyGroupId = new PolicyGroupId();
    $policyGroupName = new PolicyGroupName('テストポリシーグループ');
    $policyGroupDescription = new PolicyGroupDescription('簡単な説明');
    $policyId_1 = new PolicyId();
    $policyId_2 = new PolicyId();
    $policies = new PolicyIdCollection([$policyId_1, $policyId_2]);

    // When
    $policyGroup = new PolicyGroup($policyGroupId, $policyGroupName, $policyGroupDescription, $policies);
    $policyGroup->removePolicy($policyId_2);

    // Then
    $this->assertInstanceOf(PolicyGroup::class, $policyGroup);
    $this->assertEquals($policyGroupId, $policyGroup->getId());
    $this->assertEquals($policyGroupName, $policyGroup->getName());
    $this->assertCount(1, $policyGroup->getPolicies()->toArray());
  }
}
