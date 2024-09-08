<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Infrastructure\Persistence\MinioProfileIconRepository;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MinioProfileIconRepositoryTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    Storage::fake('s3');
    config()->set('logging.default', 'stderr');
  }

  public function testGetDefaultAll()
  {
    // Given
    Storage::disk('s3')->put('/profile-icons/defaults/icon1.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon2.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon3.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon4.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon5.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon6.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon7.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon8.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon9.png', 'test');
    Storage::disk('s3')->put('/profile-icons/defaults/icon10.png', 'test');
    
    $repository = new MinioProfileIconRepository();

    // When
    $icons = $repository->getDefaultAll()->toArray();

    // Then
    $this->assertCount(10, $icons["files"]);
  }
}
