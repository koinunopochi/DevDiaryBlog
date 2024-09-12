<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\PolicyGroup;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyGroupDescription;
use App\Domain\ValueObjects\PolicyGroupIdCollection;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyIdCollection;
use App\Infrastructure\Persistence\EloquentPolicyGroupRepository;
use App\Models\EloquentPolicyGroup;
use App\Models\EloquentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentPolicyGroupRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentPolicyGroupRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentPolicyGroupRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentPolicyGroup::factory()->count(3)->create();

    // When
    $policyGroups = $this->repository->all();

    // Then
    $this->assertCount(3, $policyGroups->toArray());
    $this->assertInstanceOf(PolicyGroupIdCollection::class, $policyGroups);
    foreach ($policyGroups as $policyGroupId) {
      $this->assertInstanceOf(PolicyGroupId::class, $policyGroupId);
    }
  }

  public function test_findById(): void
  {
    // Given
    $createdPolicyGroup = EloquentPolicyGroup::factory()->create();

    // When
    $policyGroup = $this->repository->findById(new PolicyGroupId($createdPolicyGroup->id));

    // Then
    $this->assertNotNull($policyGroup);
    $this->assertEquals($createdPolicyGroup->name, $policyGroup->getName()->toString());
    $this->assertEquals($createdPolicyGroup->description, $policyGroup->getDescription()->toString());
  }

  public function test_findByName(): void
  {
    // Given
    $createdPolicyGroup = EloquentPolicyGroup::factory()->create();

    // When
    $policyGroup = $this->repository->findByName(new PolicyGroupName($createdPolicyGroup->name));

    // Then
    $this->assertNotNull($policyGroup);
    $this->assertEquals($createdPolicyGroup->id, $policyGroup->getId()->toString());
  }

  public function test_save_create_new(): void
  {
    // Given
    $newPolicyGroup = new PolicyGroup(
      new PolicyGroupId(),
      new PolicyGroupName('New PolicyGroup'),
      new PolicyGroupDescription('New Description'),
      new PolicyIdCollection([])
    );

    // When
    $this->repository->save($newPolicyGroup);

    // Then
    $policyGroupFromDatabase = EloquentPolicyGroup::find($newPolicyGroup->getId()->toString());

    $this->assertNotNull($policyGroupFromDatabase);
    $this->assertEquals($newPolicyGroup->getName()->toString(), $policyGroupFromDatabase->name);
    $this->assertEquals($newPolicyGroup->getDescription()->toString(), $policyGroupFromDatabase->description);

    // PolicyIdCollection と EloquentPolicyGroup のポリシーを比較
    $this->assertCount(0, $policyGroupFromDatabase->policies);
    $this->assertEquals(
      $newPolicyGroup->getPolicies()->toArray(),
      $policyGroupFromDatabase->policies->pluck('id')->toArray()
    );
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingPolicyGroup = EloquentPolicyGroup::factory()->create();
    $policy = EloquentPolicy::factory()->create();
    $existingPolicyGroup->policies()->attach($policy);

    // When
    $updatedPolicyGroup = new PolicyGroup(
      new PolicyGroupId($existingPolicyGroup->id),
      new PolicyGroupName('Updated Name'),
      new PolicyGroupDescription('Updated Description'),
      new PolicyIdCollection([new PolicyId($policy->id)])
    );

    $this->repository->save($updatedPolicyGroup);

    // Then
    $policyGroupFromDatabase = EloquentPolicyGroup::find($existingPolicyGroup->id);

    $this->assertNotNull($policyGroupFromDatabase);
    $this->assertEquals($updatedPolicyGroup->getName()->toString(), $policyGroupFromDatabase->name);
    $this->assertEquals($updatedPolicyGroup->getDescription()->toString(), $policyGroupFromDatabase->description);

    // PolicyIdCollection と EloquentPolicyGroup のポリシーを比較
    $this->assertCount(1, $policyGroupFromDatabase->policies);
    $this->assertEquals(
      $updatedPolicyGroup->getPolicies()->toArray(),
      $policyGroupFromDatabase->policies->pluck('id')->map(fn($id) => new PolicyId($id))->toArray()
    );

    // 更新前と更新後で、名前と説明が変更されていることを確認
    $this->assertNotEquals($existingPolicyGroup->name, $policyGroupFromDatabase->name);
    $this->assertNotEquals($existingPolicyGroup->description, $policyGroupFromDatabase->description);
  }

  public function test_delete(): void
  {
    // Given
    $policyGroupToDelete = EloquentPolicyGroup::factory()->create();

    // When
    $policyGroup = $this->repository->findById(new PolicyGroupId($policyGroupToDelete->id));
    $this->assertNotNull($policyGroup);

    $this->repository->delete($policyGroup);

    // Then
    $this->assertDatabaseMissing('policy_groups', [
      'id' => $policyGroupToDelete->id
    ]);
  }

  public function test_getPolicies(): void
  {
    // Given
    $policyGroup = EloquentPolicyGroup::factory()->create();
    $policies = EloquentPolicy::factory()->count(3)->create();
    $policyGroup->policies()->attach($policies->pluck('id'));

    // When
    $policyIds = $this->repository->getPolicies(new PolicyGroupId($policyGroup->id));

    // Then
    $this->assertCount(3, $policyIds->toArray());
    $this->assertInstanceOf(PolicyIdCollection::class, $policyIds);
    foreach ($policyIds as $policyId) {
      $this->assertInstanceOf(PolicyId::class, $policyId);
      $this->assertTrue($policies->pluck('id')->contains($policyId->toString()));
    }
  }

  public function test_addPolicy(): void
  {
    // Given
    $policyGroup = EloquentPolicyGroup::factory()->create();
    $policy = EloquentPolicy::factory()->create();

    // When
    $this->repository->addPolicy(new PolicyGroupId($policyGroup->id), new PolicyId($policy->id));

    // Then
    $this->assertDatabaseHas('policy_group_policy', [
      'policy_group_id' => $policyGroup->id,
      'policy_id' => $policy->id
    ]);
  }

  public function test_removePolicy(): void
  {
    // Given
    $policyGroup = EloquentPolicyGroup::factory()->create();
    $policy = EloquentPolicy::factory()->create();
    $policyGroup->policies()->attach($policy->id);

    // When
    $this->repository->removePolicy(new PolicyGroupId($policyGroup->id), new PolicyId($policy->id));

    // Then
    $this->assertDatabaseMissing('policy_group_policy', [
      'policy_group_id' => $policyGroup->id,
      'policy_id' => $policy->id
    ]);
  }
}
