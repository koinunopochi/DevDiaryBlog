<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Policy;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyName;
use App\Domain\ValueObjects\PolicyDescription;
use App\Domain\ValueObjects\PolicyDocument;
use App\Infrastructure\Persistence\EloquentPolicyRepository;
use App\Models\EloquentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentPolicyRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentPolicyRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentPolicyRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentPolicy::factory()->count(3)->create();

    // When
    $policies = $this->repository->all();

    // Then
    $this->assertCount(3, $policies);
    $this->assertContainsOnlyInstancesOf(Policy::class, $policies);
  }

  public function test_findById(): void
  {
    // Given
    $createdPolicy = EloquentPolicy::factory()->create();

    // When
    $policy = $this->repository->findById(new PolicyId($createdPolicy->id));

    // Then
    $this->assertNotNull($policy);
    $this->assertEquals($createdPolicy->name, $policy->getName()->toString());
    $this->assertEquals($createdPolicy->description, $policy->getDescription()->toString());
  }

  public function test_findByName(): void
  {
    // Given
    $createdPolicy = EloquentPolicy::factory()->create();

    // When
    $policy = $this->repository->findByName(new PolicyName($createdPolicy->name));

    // Then
    $this->assertNotNull($policy);
    $this->assertEquals($createdPolicy->id, $policy->getId()->toString());
  }

  public function test_save_create_new(): void
  {
    // Given
    $newPolicy = new Policy(
      new PolicyId(),
      new PolicyName('New Policy'),
      new PolicyDescription('New Description'),
      new PolicyDocument([
        'Version' => '2024-08-29',
        'Statement' => [
          [
            'Effect' => 'Allow',
            'Action' => ['action1', 'action2'],
            'Resource' => ['resource1']
          ]
        ]
      ])
    );

    // When
    $this->repository->save($newPolicy);

    // Then
    $policyFromDatabase = EloquentPolicy::find($newPolicy->getId()->toString());

    $this->assertNotNull($policyFromDatabase);
    $this->assertEquals($newPolicy->getName()->toString(), $policyFromDatabase->name);
    $this->assertEquals($newPolicy->getDescription()->toString(), $policyFromDatabase->description);
    $this->assertEquals($newPolicy->getDocument()->toArray(), $policyFromDatabase->document);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingPolicy = EloquentPolicy::factory()->create();

    // When
    $updatedPolicy = new Policy(
      new PolicyId($existingPolicy->id),
      new PolicyName('Updated Name'),
      new PolicyDescription('Updated Description'),
      new PolicyDocument([
        'Version' => '2024-09-01',
        'Statement' => [
          [
            'Effect' => 'Deny',
            'Action' => ['action3'],
            'Resource' => ['resource2', 'resource3']
          ]
        ]
      ])
    );

    $this->repository->save($updatedPolicy);

    // Then
    $policyFromDatabase = EloquentPolicy::find($existingPolicy->id);

    $this->assertNotNull($policyFromDatabase);
    $this->assertEquals($updatedPolicy->getName()->toString(), $policyFromDatabase->name);
    $this->assertEquals($updatedPolicy->getDescription()->toString(), $policyFromDatabase->description);
    $this->assertEquals($updatedPolicy->getDocument()->toArray(), $policyFromDatabase->document);
  }

  public function test_delete(): void
  {
    // Given
    $policyToDelete = EloquentPolicy::factory()->create();

    // When
    $policy = $this->repository->findById(new PolicyId($policyToDelete->id));
    $this->assertNotNull($policy);

    $this->repository->delete($policy);

    // Then
    $this->assertDatabaseMissing('policies', [
      'id' => $policyToDelete->id
    ]);
  }

  public function test_getByVersion(): void
  {
    // Given
    EloquentPolicy::factory()->create(['document' => ['Version' => '2024-08-29']]);
    EloquentPolicy::factory()->create(['document' => ['Version' => '2024-08-29']]);
    EloquentPolicy::factory()->create(['document' => ['Version' => '2024-09-01']]);

    // When
    $policies = $this->repository->getByVersion('2024-08-29');

    // Then
    $this->assertCount(2, $policies);
    $this->assertContainsOnlyInstancesOf(Policy::class, $policies);
    foreach ($policies as $policy) {
      $this->assertEquals('2024-08-29', $policy->getDocument()->toArray()['Version']);
    }
  }
}
