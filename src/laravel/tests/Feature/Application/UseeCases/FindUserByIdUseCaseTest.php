<?php

namespace Tests\Feature\Application\UseeCases;

use App\Application\UseCases\FindUserByIdUseCase;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User as EloquentUser;

class FindUserByIdUseCaseTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */
  public function testCanGetUserById(): void
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();
    $userId = new UserId($eloquentUser->id);

    $useCase = new FindUserByIdUseCase(new EloquentUserRepository());
    // When
    $result = $useCase->execute($userId);

    // Then
    $this->assertInstanceOf(User::class, $result);
    $this->assertEquals($eloquentUser->id, $result->getUserId()->toString());
    $this->assertEquals($eloquentUser->name, $result->getUsername()->toString());
    $this->assertEquals($eloquentUser->email, $result->getEmail()->toString());
  }

  /**
   * @test
   */
  public function testCannotGetUserById(): void
  {
    // Given
    $userId = new UserId();

    $useCase = new FindUserByIdUseCase(new EloquentUserRepository());
    // When
    $result = $useCase->execute($userId);

    // Then
    $this->assertNull($result);
  }
}
