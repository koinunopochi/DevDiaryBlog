<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\UserRole;
use App\Domain\ValueObjects\UserRoleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\RoleId;
use App\Infrastructure\Persistence\EloquentUserRoleRepository;
use App\Models\EloquentUserRole;
use App\Models\User as EloquentUser;
use App\Models\EloquentRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Domain\ValueObjects\DateTime;

class EloquentUserRoleRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentUserRoleRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentUserRoleRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentUserRole::factory()->count(3)->create();

    // When
    $userRoles = $this->repository->all();

    // Then
    $this->assertCount(3, $userRoles);
    $this->assertContainsOnlyInstancesOf(UserRole::class, $userRoles);
  }

  public function test_findById(): void
  {
    // Given
    $createdUserRole = EloquentUserRole::factory()->create();

    // When
    $userRole = $this->repository->findById(new UserRoleId($createdUserRole->id));

    // Then
    $this->assertNotNull($userRole);
    $this->assertEquals($createdUserRole->user_id, $userRole->getUserId()->toString());
    $this->assertEquals($createdUserRole->role_id, $userRole->getRoleId()->toString());
  }

  public function test_findByUserId(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    EloquentUserRole::factory()->count(2)->create(['user_id' => $user->id]);
    EloquentUserRole::factory()->create(); // Another user's role

    // When
    $userRoles = $this->repository->findByUserId(new UserId($user->id));

    // Then
    $this->assertCount(2, $userRoles);
    $this->assertContainsOnlyInstancesOf(UserRole::class, $userRoles);
  }

  public function test_findByRoleId(): void
  {
    // Given
    $role = EloquentRole::factory()->create();
    EloquentUserRole::factory()->count(2)->create(['role_id' => $role->id]);
    EloquentUserRole::factory()->create(); // Another role

    // When
    $userRoles = $this->repository->findByRoleId(new RoleId($role->id));

    // Then
    $this->assertCount(2, $userRoles);
    $this->assertContainsOnlyInstancesOf(UserRole::class, $userRoles);
  }

  public function test_save_create_new(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    $role = EloquentRole::factory()->create();
    $assignedBy = EloquentUser::factory()->create();
    $newUserRole = new UserRole(
      new UserRoleId(),
      new UserId($user->id),
      new RoleId($role->id),
      new DateTime(),
      new UserId($assignedBy->id)
    );

    // When
    $this->repository->save($newUserRole);

    // Then
    $this->assertDatabaseHas('user_roles', [
      'id' => $newUserRole->getId()->toString(),
      'user_id' => $user->id,
      'role_id' => $role->id,
      'assigned_by' => $assignedBy->id,
    ]);
  }

  public function test_delete(): void
  {
    // Given
    $userRoleToDelete = EloquentUserRole::factory()->create();

    // When
    $userRole = $this->repository->findById(new UserRoleId($userRoleToDelete->id));
    $this->assertNotNull($userRole);
    $this->repository->delete($userRole);

    // Then
    $this->assertDatabaseMissing('user_roles', [
      'id' => $userRoleToDelete->id
    ]);
  }

  public function test_findByAssignedDateRange(): void
  {
    // Given
    $startDate = new DateTime('2024-01-01T00:00:00+09:00');
    $endDate = new DateTime('2024-12-31T00:00:00+09:00');

    EloquentUserRole::factory()->create(['assigned_at' => '2024-01-01T00:00:00+09:00']);
    EloquentUserRole::factory()->create(['assigned_at' => '2024-09-08T16:52:07+09:00']);
    EloquentUserRole::factory()->create(['assigned_at' => '2025-01-01T00:00:00+09:00']);

    // When
    $userRoles = $this->repository->findByAssignedDateRange($startDate, $endDate);

    // Then
    $this->assertCount(2, $userRoles);
  }

  public function test_findByAssignedBy(): void
  {
    // Given
    $assignedBy = EloquentUser::factory()->create();
    EloquentUserRole::factory()->count(2)->create(['assigned_by' => $assignedBy->id]);
    EloquentUserRole::factory()->create(); // Assigned by another user

    // When
    $userRoles = $this->repository->findByAssignedBy(new UserId($assignedBy->id));

    // Then
    $this->assertCount(2, $userRoles);
  }

  public function test_userHasRole(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    $role = EloquentRole::factory()->create();
    EloquentUserRole::factory()->create([
      'user_id' => $user->id,
      'role_id' => $role->id,
    ]);

    // When
    $hasRole = $this->repository->userHasRole(new UserId($user->id), new RoleId($role->id));

    // Then
    $this->assertTrue($hasRole);
  }
}
