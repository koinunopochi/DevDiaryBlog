<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\DataTransferObjects\SaveProfileDTO;
use App\Application\UseCases\SaveProfileUseCase;
use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Models\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SaveProfileUseCaseTest extends TestCase
{
  use RefreshDatabase;
  /**
   * @test
   */
  public function testCanSaveProfile(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    // Request
    $request = new Request([
      'displayName' => 'Test',
      'bio' => 'Test',
      'avatarUrl' => 'https://test.com',
      'socialLinks' => [
        'twitter' => 'https://twitter.com/test',
        'instagram' => 'https://instagram.com/test',
        'facebook' => 'https://facebook.com/test',
      ],
    ]);
    $dto = new SaveProfileDTO($request, $user->id);
    $useCase = new SaveProfileUseCase(new EloquentUserProfileRepository());

    // When
    $useCase->execute($dto);
    $profile = (new EloquentUserProfileRepository())->findById(new UserId($user->id));

    // Then
    $this->assertInstanceOf(Profile::class, $profile);
    $this->assertEquals($profile->getDisplayName()->toString(), 'Test');
    $this->assertEquals($profile->getBio()->toString(), 'Test');
    $this->assertEquals($profile->getAvatarUrl()->toString(), 'https://test.com');
    $this->assertEquals($profile->getSocialLinks()->toArray(), [
      'twitter' => 'https://twitter.com/test',
      'instagram' => 'https://instagram.com/test',
      'facebook' => 'https://facebook.com/test',
    ]);
  }
}
