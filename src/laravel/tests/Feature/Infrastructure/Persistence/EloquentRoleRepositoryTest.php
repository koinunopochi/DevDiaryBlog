<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Role;
use App\Domain\ValueObjects\PolicyGroupIdCollection;
use App\Domain\ValueObjects\RoleId;
use App\Domain\ValueObjects\RoleName;
use App\Domain\ValueObjects\RoleDescription;
use App\Domain\ValueObjects\PolicyIdCollection;
use App\Domain\ValueObjects\PolicyId;
use App\Infrastructure\Persistence\EloquentRoleRepository;
use App\Models\EloquentRole;
use App\Models\EloquentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentRoleRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentRoleRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentRoleRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentRole::factory()->count(3)->create();

    // When
    $roles = $this->repository->all();

    // Then
    $this->assertCount(3, $roles);
    $this->assertContainsOnlyInstancesOf(Role::class, $roles);
  }

  public function test_findById(): void
  {
    // Given
    $createdRole = EloquentRole::factory()->create();

    // When
    $role = $this->repository->findById(new RoleId($createdRole->id));

    // Then
    $this->assertNotNull($role);
    $this->assertEquals($createdRole->name, $role->getName()->toString());
    $this->assertEquals($createdRole->description, $role->getDescription()->toString());
  }

  public function test_findByName(): void
  {
    // Given
    $createdRole = EloquentRole::factory()->create();

    // When
    $role = $this->repository->findByName(new RoleName($createdRole->name));

    // Then
    $this->assertNotNull($role);
    $this->assertEquals($createdRole->id, $role->getId()->toString());
  }

  public function test_save_create_new(): void
  {
    // Given
    $newRole = new Role(
      new RoleId(),
      new RoleName('New Role'),
      new RoleDescription('New Description'),
      new PolicyIdCollection([]),
      new PolicyGroupIdCollection([]),
    );

    // When
    $this->repository->save($newRole);

    // Then
    $roleFromDatabase = EloquentRole::find($newRole->getId()->toString());

    $this->assertNotNull($roleFromDatabase);
    $this->assertEquals($newRole->getName()->toString(), $roleFromDatabase->name);
    $this->assertEquals($newRole->getDescription()->toString(), $roleFromDatabase->description);

    // PolicyIdCollection と EloquentRole のポリシーを比較
    $this->assertCount(0, $roleFromDatabase->policies);
    $this->assertEquals(
      $newRole->getPolicies()->toArray(),
      $roleFromDatabase->policies->pluck('id')->toArray()
    );

    // PolicyGroupIdCollection と EloquentRole のポリシーグループを比較
    $this->assertCount(0, $roleFromDatabase->policyGroups);
    $this->assertEquals(
      $newRole->getPolicyGroups()->toArray(),
      $roleFromDatabase->policyGroups->pluck('id')->toArray()
    );
  }


  public function test_save_update_existing(): void
  {
    // Given
    $existingRole = EloquentRole::factory()->create();

    // When
    $updatedRole = new Role(
      new RoleId($existingRole->id),
      new RoleName('Updated Name'),
      new RoleDescription('Updated Description'),
      new PolicyIdCollection([]),
      new PolicyGroupIdCollection([]),
    );

    $this->repository->save($updatedRole);

    // Then
    $roleFromDatabase = EloquentRole::find($existingRole->id);

    $this->assertNotNull($roleFromDatabase);
    $this->assertEquals($updatedRole->getName()->toString(), $roleFromDatabase->name);
    $this->assertEquals($updatedRole->getDescription()->toString(), $roleFromDatabase->description);

    // PolicyIdCollection と EloquentRole のポリシーを比較
    $this->assertCount(0, $roleFromDatabase->policies);
    $this->assertEquals(
      $updatedRole->getPolicies()->toArray(),
      $roleFromDatabase->policies->pluck('id')->toArray()
    );

    // PolicyGroupIdCollection と EloquentRole のポリシーグループを比較
    $this->assertCount(0, $roleFromDatabase->policyGroups);
    $this->assertEquals(
      $updatedRole->getPolicyGroups()->toArray(),
      $roleFromDatabase->policyGroups->pluck('id')->toArray()
    );

    // 更新前と更新後で、名前と説明が変更されていることを確認
    $this->assertNotEquals($existingRole->name, $roleFromDatabase->name);
    $this->assertNotEquals($existingRole->description, $roleFromDatabase->description);
  }

  public function test_delete(): void
  {
    // Given
    $roleToDelete = EloquentRole::factory()->create();

    // When
    $role = $this->repository->findById(new RoleId($roleToDelete->id));
    $this->assertNotNull($role);

    $this->repository->delete($role);

    // Then
    $this->assertDatabaseMissing('roles', [
      'id' => $roleToDelete->id
    ]);
  }

  public function test_findByPolicy(): void
  {
    // Given
    $policy = EloquentPolicy::factory()->create();
    $roleWithPolicy = EloquentRole::factory()->create();
    $roleWithPolicy->policies()->attach($policy);
    EloquentRole::factory()->count(2)->create(); // roles without the policy

    // When
    $roles = $this->repository->findByPolicy(new PolicyId($policy->id));

    // Then
    $this->assertCount(1, $roles);
    $this->assertContainsOnlyInstancesOf(Role::class, $roles);
    $this->assertEquals($roleWithPolicy->id, $roles->first()->getId()->toString());
  }

  public function test_addPolicy(): void
  {
    // Given
    $role = EloquentRole::factory()->create();
    $policy = EloquentPolicy::factory()->create();

    // When
    $this->repository->addPolicy(
      $this->repository->findById(new RoleId($role->id)),
      new PolicyId($policy->id)
    );

    // Then
    $this->assertDatabaseHas('role_policy', [
      'role_id' => $role->id,
      'policy_id' => $policy->id
    ]);
  }

  public function test_removePolicy(): void
  {
    // Given
    $role = EloquentRole::factory()->create();
    $policy = EloquentPolicy::factory()->create();
    $role->policies()->attach($policy);

    // When
    $this->repository->removePolicy(
      $this->repository->findById(new RoleId($role->id)),
      new PolicyId($policy->id)
    );

    // Then
    $this->assertDatabaseMissing('role_policy', [
      'role_id' => $role->id,
      'policy_id' => $policy->id
    ]);
  }
}
