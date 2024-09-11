<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupIdCollection;
use Tests\TestCase;

class PolicyGroupIdCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatePolicyGroupIdCollectionWithValidData()
    {
        // Given
        $policyGroupId1 = new PolicyGroupId();
        $policyGroupId2 = new PolicyGroupId();

        // When
        $policyGroupIdCollection = new PolicyGroupIdCollection([$policyGroupId1, $policyGroupId2]);

        // Then
        $this->assertInstanceOf(PolicyGroupIdCollection::class, $policyGroupIdCollection);
        $this->assertCount(2, $policyGroupIdCollection->toArray());
    }

    /**
     * @test
     */
    public function testCreatePolicyGroupIdCollectionWithInvalidData()
    {
        // Given
        $policyGroupId1 = "invalid policy id";

        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        new PolicyGroupIdCollection([$policyGroupId1]);
    }

    /**
     * @test
     */
    public function testToArray()
    {
        // Given
        $policyGroupId1 = new PolicyGroupId();
        $policyGroupId2 = new PolicyGroupId();
        $policyGroupIdCollection = new PolicyGroupIdCollection([$policyGroupId1, $policyGroupId2]);

        // When
        $result = $policyGroupIdCollection->toArray();

        // Then
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(PolicyGroupId::class, $result);
    }

    /**
     * @test
     */
    public function testAdd()
    {
        // Given
        $policyGroupId1 = new PolicyGroupId();
        $policyGroupId2 = new PolicyGroupId();
        $policyGroupIdCollection = new PolicyGroupIdCollection([$policyGroupId1]);

        // When
        $newPolicyGroupIdCollection = $policyGroupIdCollection->add($policyGroupId2);

        // Then
        $this->assertNotSame($policyGroupIdCollection, $newPolicyGroupIdCollection);
        $this->assertCount(1, $policyGroupIdCollection->toArray());
        $this->assertCount(2, $newPolicyGroupIdCollection->toArray());
    }

    /**
     * @test
     */
    public function testRemove()
    {
        // Given
        $policyGroupId1 = new PolicyGroupId();
        $policyGroupId2 = new PolicyGroupId();
        $policyGroupIdCollection = new PolicyGroupIdCollection([$policyGroupId1, $policyGroupId2]);

        // When
        $newPolicyGroupIdCollection = $policyGroupIdCollection->remove($policyGroupId2);

        // Then
        $this->assertNotSame($policyGroupIdCollection, $newPolicyGroupIdCollection);
        $this->assertCount(2, $policyGroupIdCollection->toArray());
        $this->assertCount(1, $newPolicyGroupIdCollection->toArray());
    }

    /**
     * @test
     */
    public function testContains()
    {
        // Given
        $policyGroupId1 = new PolicyGroupId();
        $policyGroupId2 = new PolicyGroupId();
        $policyGroupIdCollection = new PolicyGroupIdCollection([$policyGroupId1]);

        // When
        $result1 = $policyGroupIdCollection->contains($policyGroupId1);
        $result2 = $policyGroupIdCollection->contains($policyGroupId2);

        // Then
        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    /**
     * @test
     */
    public function testCount()
    {
        // Given
        $policyGroupId1 = new PolicyGroupId();
        $policyGroupId2 = new PolicyGroupId();
        $policyGroupIdCollection = new PolicyGroupIdCollection([$policyGroupId1, $policyGroupId2]);

        // When
        $result = $policyGroupIdCollection->count();

        // Then
        $this->assertEquals(2, $result);
    }
}
