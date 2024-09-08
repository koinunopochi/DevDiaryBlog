<?php

namespace Tests\Unit\Application\UseCases;

use App\Application\DataTransferObjects\SaveUserDTO;
use App\Application\UseCases\SaveUserUseCase;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Models\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveUserUseCaseTest extends TestCase
{
  private SaveUserUseCase $useCase;

  use RefreshDatabase;

  const SAMPLE_NAME = 'sampleName';
  const SAMPLE_EMAIL = 'sample@email.com';
  const SAMPLE_PASSWORD = 'row_password_A1';

  /**
   * @test
   */
  public function testCanSaveUser()
  {
    // Given
    $user = EloquentUser::factory()->create();

    $userId = new UserId($user->id);
    $name = new Username(self::SAMPLE_NAME);
    $email = new Email(self::SAMPLE_EMAIL);
    $password = new Password(self::SAMPLE_PASSWORD);

    $dto = new SaveUserDTO($name, $email, $password);

    $saveUserUseCase = new SaveUserUseCase(new EloquentUserRepository());

    // When
    $saveUserUseCase->execute($dto, $userId);
    $savedUser = (new EloquentUserRepository())->findById($userId);

    // Then
    $this->assertEquals($userId, $savedUser->getUserId());
    $this->assertEquals($name, $savedUser->getUsername());
    $this->assertEquals($email, $savedUser->getEmail());
    $this->assertEquals($password, $savedUser->getPassword());
  }

  /**
   * @test
   * @dataProvider providePartialDtoData
   */
  public function testCanSaveUserWithPartialDto($name, $email, $password)
  {
    // Given
    $user = EloquentUser::factory()->create();

    $userId = new UserId($user->id);
    $name = $name ? new Username($name) : null;
    $email = $email ? new Email($email) : null;
    $password = $password ? new Password($password) : null;

    $dto = new SaveUserDTO($name, $email, $password);

    $saveUserUseCase = new SaveUserUseCase(new EloquentUserRepository());

    // When
    $saveUserUseCase->execute($dto, $userId);
    $savedUser = (new EloquentUserRepository())->findById($userId);

    // Then
    // 更新された項目のみアサーション、更新されなかった項目は元の値のままのはず
    if ($name) {
      $this->assertEquals($name, $savedUser->getUsername());
    } else {
      $this->assertEquals(new Username($user->name), $savedUser->getUsername());
    }

    if ($email) {
      $this->assertEquals($email, $savedUser->getEmail());
    } else {
      $this->assertEquals(new Email($user->email), $savedUser->getEmail());
    }

    if ($password) {
      $this->assertEquals($password, $savedUser->getPassword());
    } else {
      $this->assertEquals(new Password($user->password), $savedUser->getPassword());
    }
  }

  public static function providePartialDtoData()
  {
    return [
      'name only' => [self::SAMPLE_NAME, null, null],
      'email only' => [null, self::SAMPLE_EMAIL, null],
      'password only' => [null, null, self::SAMPLE_PASSWORD],
      'name and email' => [self::SAMPLE_NAME, self::SAMPLE_EMAIL, null],
      'name and password' => [self::SAMPLE_NAME, null, self::SAMPLE_PASSWORD],
      'email and password' => [null, self::SAMPLE_EMAIL, self::SAMPLE_PASSWORD],
      'all null' => [null, null, null],
    ];
  }
}
