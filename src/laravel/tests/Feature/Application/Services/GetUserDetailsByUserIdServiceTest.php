<?php

namespace Tests\Feature\Application\Services;

use App\Application\Services\GetUserDetailsByUserIdService;
use App\Application\UseCases\FindProfileByUserIdUseCase;
use App\Application\UseCases\FindUserByIdUseCase;
use App\Domain\Entities\UserDetails;
use App\Models\User as EloquentUser;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Infrastructure\Persistence\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetUserDetailsByUserIdServiceTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function testExistsUser()
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();
    $userId = new UserId($eloquentUser->id);

    $userUseCase = new FindUserByIdUseCase(new EloquentUserRepository());
    $profileUseCase = new FindProfileByUserIdUseCase(new EloquentUserProfileRepository());

    $service = new GetUserDetailsByUserIdService($userUseCase, $profileUseCase);

    // When
    $result = $service->execute($userId);

    // Then
    $this->assertInstanceOf(UserDetails::class, $result);
  }

  /** @test */
  public function testNotExistsUser()
  {
    // Given
    $userId = new UserId();

    $userUseCase = new FindUserByIdUseCase(new EloquentUserRepository());
    $profileUseCase = new FindProfileByUserIdUseCase(new EloquentUserProfileRepository());

    $service = new GetUserDetailsByUserIdService($userUseCase, $profileUseCase);

    // When
    $result = $service->execute($userId);

    // Then
    $this->assertNull($result);
  }
}
