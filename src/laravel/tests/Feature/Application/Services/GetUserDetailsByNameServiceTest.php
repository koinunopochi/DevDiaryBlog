<?php

namespace Tests\Feature\Application\Services;

use App\Application\Services\GetUserDetailsByNameService;
use App\Application\UseCases\FindProfileByUserIdUseCase;
use App\Application\UseCases\FindUserByNameUseCase;
use App\Domain\Entities\UserDetails;
use App\Models\User as EloquentUser;
use App\Domain\ValueObjects\Username;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Infrastructure\Persistence\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetUserDetailsByNameServiceTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function testExistsUser()
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();
    $username = new Username($eloquentUser->name);

    $userUseCase = new FindUserByNameUseCase(new EloquentUserRepository());
    $profileUseCase = new FindProfileByUserIdUseCase(new EloquentUserProfileRepository());

    $service = new GetUserDetailsByNameService($userUseCase, $profileUseCase);

    // When
    $result = $service->execute($username);

    // Then
    $this->assertInstanceOf(UserDetails::class, $result);
  }

  /** @test */
  public function testNotExistsUser()
  {
    // Given
    $username = new Username('testuser');

    $userUseCase = new FindUserByNameUseCase(new EloquentUserRepository());
    $profileUseCase = new FindProfileByUserIdUseCase(new EloquentUserProfileRepository());

    $service = new GetUserDetailsByNameService($userUseCase, $profileUseCase);

    // When
    $result = $service->execute($username);

    // Then
    $this->assertNull($result);
  }
}
