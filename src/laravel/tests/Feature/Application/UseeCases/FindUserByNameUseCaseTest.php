<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\FindUserByNameUseCase;
use App\Models\User as EloquentUser;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\Username;
use App\Infrastructure\Persistence\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindUserByNameUseCaseTest extends TestCase
{
  use RefreshDatabase;
  private FindUserByNameUseCase $findUserByNameUseCase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->findUserByNameUseCase = new FindUserByNameUseCase(new EloquentUserRepository());
  }

  public function test_it_should_return_user_when_user_exists()
  {
    // Given
    $user = EloquentUser::factory()->create();

    // When
    $result = $this->findUserByNameUseCase->execute(new Username($user->name));

    // Then
    $this->assertInstanceOf(User::class, $result);
  }

  public function test_it_should_return_null_when_user_does_not_exist()
  {
    // Given
    $username = 'not_exists_user';

    // When
    $result = $this->findUserByNameUseCase->execute(new Username($username));

    // Then
    $this->assertNull($result);
  }
}
