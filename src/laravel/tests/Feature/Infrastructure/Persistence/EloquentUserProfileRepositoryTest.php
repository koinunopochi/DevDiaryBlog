<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\SocialLinkCollection;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Models\EloquentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentUserProfileRepositoryTest extends TestCase
{
  use RefreshDatabase; // テストごとにデータベースをリフレッシュ

  public function test_all(): void
  {
    // Given
    EloquentProfile::factory()->count(3)->create();

    // When
    $repository = new EloquentUserProfileRepository();
    $profiles = $repository->all();

    // Then
    $this->assertCount(3, $profiles);
  }

  public function test_findById(): void
  {
    // Given
    $createdProfile = EloquentProfile::factory()->create();

    // When
    $repository = new EloquentUserProfileRepository();
    $profile = $repository->findById(new UserId($createdProfile->user_id));

    // Then
    $this->assertNotNull($profile);
    $this->assertEquals($createdProfile->display_name, $profile->getDisplayName()->toString());
  }

  public function test_save_create_new(): void
  {
    $repository = new EloquentUserProfileRepository();

    // Given
    $newProfile = new Profile(
      new UserId(),
      new DisplayName('New User'),
      new UserBio('New bio'),
      new Url('https://new-avatar.com'),
      new SocialLinkCollection([
        'twitter' => 'https://twitter.com/new_user',
      ]),
    );

    // When
    $repository->save($newProfile);

    // Then
    $profileFromDatabase = EloquentProfile::where('user_id', $newProfile->getUserId()->toString())->first();

    $this->assertNotNull($profileFromDatabase);
    $this->assertEquals($newProfile->getDisplayName()->toString(), $profileFromDatabase->display_name);
    $this->assertEquals($newProfile->getBio()->toString(), $profileFromDatabase->bio);
    $this->assertEquals($newProfile->getAvatarUrl()->toString(), $profileFromDatabase->avatar_url);
    $this->assertEquals($newProfile->getSocialLinks()->toArray(), json_decode($profileFromDatabase->social_links, true));
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingProfile = EloquentProfile::factory()->create();

    $repository = new EloquentUserProfileRepository();

    // When
    $updatedProfile = new Profile(
      new UserId($existingProfile->user_id),
      new DisplayName('Updated Name'),
      new UserBio('Updated bio'),
      new Url('https://updated-avatar.com'),
      new SocialLinkCollection([
        'instagram' => 'https://instagram.com/updated_user',
      ])
    );

    $repository->save($updatedProfile);

    // Then
    $profileFromDatabase = EloquentProfile::where('user_id', $existingProfile->user_id)->first();

    $this->assertNotNull($profileFromDatabase);
    $this->assertEquals($updatedProfile->getDisplayName()->toString(), $profileFromDatabase->display_name);
    $this->assertEquals($updatedProfile->getBio()->toString(), $profileFromDatabase->bio);
    $this->assertEquals($updatedProfile->getAvatarUrl()->toString(), $profileFromDatabase->avatar_url);
    $this->assertEquals($updatedProfile->getSocialLinks()->toArray(), json_decode($profileFromDatabase->social_links, true));
  }

  public function test_delete(): void
  {
    // Given
    $profileToDelete = EloquentProfile::factory()->create();

    $repository = new EloquentUserProfileRepository();

    // When
    $profile = $repository->findById(new UserId($profileToDelete->user_id));
    $this->assertNotNull($profile);

    $repository->delete($profile);

    // Then
    $this->assertDatabaseMissing('profiles', [
      'user_id' => $profileToDelete->user_id
    ]);
  }
}
