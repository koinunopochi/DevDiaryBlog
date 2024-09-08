<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Domain\ValueObjects\UserStatus;
use App\Domain\ValueObjects\DateTime;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Models\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
  use RefreshDatabase;
  private const USER_NAME = 'testuser';
  private const USER_PASSWORD = '1234567890Aa*';
  private EloquentUserRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentUserRepository();
    config(['app.timezone' => 'Asia/Tokyo']);
  }

  public function test_all(): void
  {
    // Given
    EloquentUser::factory()->count(3)->create();

    // When
    $users = $this->repository->all();

    // Then
    $this->assertCount(3, $users);
    $this->assertContainsOnlyInstancesOf(User::class, $users);
  }

  public function test_findById(): void
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();

    // When
    $user = $this->repository->findById(new UserId($eloquentUser->id));

    // Then
    $this->assertNotNull($user);
    $this->assertEquals($eloquentUser->id, $user->getUserId()->toString());
    $this->assertEquals($eloquentUser->name, $user->getUsername()->toString());
    $this->assertEquals($eloquentUser->email, $user->getEmail()->toString());
    $this->assertEquals($eloquentUser->status, $user->getStatus()->toString());
    $this->assertEquals($eloquentUser->created_at->format('Y-m-d\TH:i:sP'), $user->getCreatedAt()->toString());
    $this->assertEquals($eloquentUser->updated_at->format('Y-m-d\TH:i:sP'), $user->getUpdatedAt()->toString());
  }

  public function test_findByEmail(): void
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();

    // When
    $user = $this->repository->findByEmail(new Email($eloquentUser->email));

    // Then
    $this->assertNotNull($user);
    $this->assertEquals($eloquentUser->id, $user->getUserId()->toString());
    $this->assertEquals($eloquentUser->name, $user->getUsername()->toString());
    $this->assertEquals($eloquentUser->email, $user->getEmail()->toString());
    $this->assertEquals($eloquentUser->status, $user->getStatus()->toString());
    $this->assertEquals($eloquentUser->created_at->format('Y-m-d\TH:i:sP'), $user->getCreatedAt()->toString());
    $this->assertEquals($eloquentUser->updated_at->format('Y-m-d\TH:i:sP'), $user->getUpdatedAt()->toString());
  }

  public function test_findByName(): void
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();

    // When
    $user = $this->repository->findByName(new Username($eloquentUser->name));

    // Then
    $this->assertNotNull($user);
    $this->assertEquals($eloquentUser->id, $user->getUserId()->toString());
    $this->assertEquals($eloquentUser->name, $user->getUsername()->toString());
    $this->assertEquals($eloquentUser->email, $user->getEmail()->toString());
    $this->assertEquals($eloquentUser->status, $user->getStatus()->toString());
    $this->assertEquals($eloquentUser->created_at->format('Y-m-d\TH:i:sP'), $user->getCreatedAt()->toString());
    $this->assertEquals($eloquentUser->updated_at->format('Y-m-d\TH:i:sP'), $user->getUpdatedAt()->toString());
  }

  public function test_save_create_new(): void
  {
    // Given
    $newUser = new User(
      new UserId(),
      new Username(self::USER_NAME),
      new Email('new@example.com'),
      new Password(self::USER_PASSWORD),
      new UserStatus(UserStatus::STATUS_ACTIVE),
      new DateTime(),
      new DateTime()
    );

    // When
    $this->repository->save($newUser);
    $createdUser = $this->repository->findById($newUser->getUserId());

    // Then
    $this->assertNotNull($createdUser);
    $this->assertEquals($newUser->getUserId()->toString(), $createdUser->getUserId()->toString());
    $this->assertEquals($newUser->getUsername()->toString(), $createdUser->getUsername()->toString());
    $this->assertEquals($newUser->getEmail()->toString(), $createdUser->getEmail()->toString());
    $this->assertEquals($newUser->getStatus()->toString(), $createdUser->getStatus()->toString());
    $this->assertEquals($newUser->getCreatedAt()->toString(), $createdUser->getCreatedAt()->toString());
    $this->assertEquals($newUser->getUpdatedAt()->toString(), $createdUser->getUpdatedAt()->toString());
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingUser = EloquentUser::factory()->create();
    $updatedUser = new User(
      new UserId($existingUser->id),
      new Username(self::USER_NAME),
      new Email('updated@example.com'),
      new Password(self::USER_PASSWORD),
      new UserStatus(UserStatus::STATUS_INACTIVE),
      new DateTime($existingUser->created_at),
      new DateTime()
    );

    // When
    $this->repository->save($updatedUser);
    $updatedUser = $this->repository->findById($updatedUser->getUserId());

    // Then
    $this->assertNotNull($updatedUser);
    $this->assertEquals($updatedUser->getUserId()->toString(), $updatedUser->getUserId()->toString());
    $this->assertEquals($updatedUser->getUsername()->toString(), $updatedUser->getUsername()->toString());
    $this->assertEquals($updatedUser->getEmail()->toString(), $updatedUser->getEmail()->toString());
    $this->assertEquals($updatedUser->getStatus()->toString(), $updatedUser->getStatus()->toString());
    $this->assertEquals($updatedUser->getCreatedAt()->toString(), $updatedUser->getCreatedAt()->toString());
    $this->assertEquals($updatedUser->getUpdatedAt()->toString(), $updatedUser->getUpdatedAt()->toString());
  }

  public function test_delete(): void
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();
    $user = new User(
      new UserId($eloquentUser->id),
      new Username($eloquentUser->name),
      new Email($eloquentUser->email),
      new Password($eloquentUser->password),
      new UserStatus($eloquentUser->status),
      new DateTime($eloquentUser->created_at),
      new DateTime($eloquentUser->updated_at)
    );

    // When
    $this->repository->delete($user);

    // Then
    $this->assertDatabaseMissing('users', [
      'id' => $user->getUserId()->toString()
    ]);
  }

  public function test_existsByName(): void
  {
    // Given
    $user = EloquentUser::factory()->create();

    // When
    $result = $this->repository->existsByName(new Username($user->name));

    // Then
    $this->assertTrue($result);
  }

  public function test_existsByName_notExists(): void
  {
    // Given
    $username = new Username('nonexisting_user');

    // When
    $result = $this->repository->existsByName($username);

    // Then
    $this->assertFalse($result);
  }
}
