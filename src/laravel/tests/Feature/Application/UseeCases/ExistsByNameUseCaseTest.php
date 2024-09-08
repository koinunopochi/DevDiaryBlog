<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\ExistsByNameUseCase;
use App\Domain\ValueObjects\Username;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Models\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExistsByNameUseCaseTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */
  public function 存在するユーザー名で実行した場合、trueを返す()
  {
    $user = EloquentUser::factory()->create(['name' => 'test_user']);
    $useCase = new ExistsByNameUseCase(new EloquentUserRepository());
    $result = $useCase->execute(new Username($user->name));
    $this->assertTrue($result);
  }

  /**
   * @test
   */
  public function 存在しないユーザー名で実行した場合、falseを返す()
  {
    $useCase = new ExistsByNameUseCase(new EloquentUserRepository());
    $result = $useCase->execute(new Username('non_existent_user'));
    $this->assertFalse($result);
  }
}
