<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyIdCollection;
use Tests\TestCase;

class PolicyIdCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function testCreatePolicyIdCollectionWithValidData()
    {
        // Given
        $policyId1 = new PolicyId();
        $policyId2 = new PolicyId();

        // When
        $policyIdCollection = new PolicyIdCollection([$policyId1, $policyId2]);

        // Then
        $this->assertInstanceOf(PolicyIdCollection::class, $policyIdCollection);
        $this->assertCount(2, $policyIdCollection->toArray());
    }

    /**
     * @test
     */
    public function testCreatePolicyIdCollectionWithInvalidData()
    {
        // Given
        $policyId1 = "invalid policy id";

        // Then
        $this->expectException(\InvalidArgumentException::class);

        // When
        new PolicyIdCollection([$policyId1]);
    }

    /**
     * @test
     */
    public function testToArray()
    {
        // Given
        $policyId1 = new PolicyId();
        $policyId2 = new PolicyId();
        $policyIdCollection = new PolicyIdCollection([$policyId1, $policyId2]);

        // When
        $result = $policyIdCollection->toArray();

        // Then
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(PolicyId::class, $result);
    }

    /**
     * @test
     */
    public function testAdd()
    {
        // Given
        $policyId1 = new PolicyId();
        $policyId2 = new PolicyId();
        $policyIdCollection = new PolicyIdCollection([$policyId1]);

        // When
        $newPolicyIdCollection = $policyIdCollection->add($policyId2);

        // Then
        $this->assertNotSame($policyIdCollection, $newPolicyIdCollection);
        $this->assertCount(1, $policyIdCollection->toArray());
        $this->assertCount(2, $newPolicyIdCollection->toArray());
    }

    /**
     * @test
     */
    public function testRemove()
    {
        // Given
        $policyId1 = new PolicyId();
        $policyId2 = new PolicyId();
        $policyIdCollection = new PolicyIdCollection([$policyId1, $policyId2]);

        // When
        $newPolicyIdCollection = $policyIdCollection->remove($policyId2);

        // Then
        $this->assertNotSame($policyIdCollection, $newPolicyIdCollection);
        $this->assertCount(2, $policyIdCollection->toArray());
        $this->assertCount(1, $newPolicyIdCollection->toArray());
    }

    /**
     * @test
     */
    public function testContains()
    {
        // Given
        $policyId1 = new PolicyId();
        $policyId2 = new PolicyId();
        $policyIdCollection = new PolicyIdCollection([$policyId1]);

        // When
        $result1 = $policyIdCollection->contains($policyId1);
        $result2 = $policyIdCollection->contains($policyId2);

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
        $policyId1 = new PolicyId();
        $policyId2 = new PolicyId();
        $policyIdCollection = new PolicyIdCollection([$policyId1, $policyId2]);

        // When
        $result = $policyIdCollection->count();

        // Then
        $this->assertEquals(2, $result);
    }
}
