<?php

namespace Tests\Feature\Application\UseeCases;

use App\Application\UseCases\FindProfileByUserIdUseCase;
use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Models\EloquentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User as EloquentUser;

class FindProfileByUserIdUseCaseTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testCanGetProfileByUserId(): void
  {
    // Given
    $eloquentUser = EloquentUser::factory()->create();
    $eloquentProfile = EloquentProfile::factory()->create([
      'user_id' => $eloquentUser->id,
    ]);
    $userId = new UserId($eloquentUser->id);

    $useCase = new FindProfileByUserIdUseCase(new EloquentUserProfileRepository());
    // When
    $result = $useCase->execute($userId);

    dump($result);
    dump($eloquentProfile);

    // Then
    $this->assertInstanceOf(Profile::class, $result);
    $this->assertEquals($eloquentUser->id, $result->getUserId()->toString());
    $this->assertEquals($eloquentProfile->bio, $result->getBio()->toString());
    $this->assertEquals($eloquentProfile->display_name, $result->getDisplayName()->toString());
    $this->assertEquals($eloquentProfile->avatar_url, $result->getAvatarUrl()->toString());
    $this->assertEquals(json_decode($eloquentProfile->social_links, true), $result->getSocialLinks()->toArray());
  }

  /**
   * @test
   */
  public function testCannotGetProfileByUserId(): void
  {
    // Given
    $userId = new UserId();

    $useCase = new FindProfileByUserIdUseCase(new EloquentUserProfileRepository());
    // When
    $result = $useCase->execute($userId);

    // Then
    $this->assertNull($result);
  }
}
