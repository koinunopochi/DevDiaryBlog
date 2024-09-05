<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\UserId;
use Illuminate\Support\Collection;

interface UserProfileRepositoryInterface
{
  /**
   * Get all UserProfiles
   *
   * @return Collection<int, Profile>
   */
  public function all(): Collection;

  /**
   * Find a UserProfile by id
   *
   * @param UserId $id
   * @return Profile|null
   */
  public function findById(UserId $id): ?Profile;

  /**
   * Save a UserProfile
   *
   * @param Profile $profile
   * @return void
   */
  public function save(Profile $profile): void;

  /**
   * Delete a UserProfile
   *
   * @param Profile $profile
   * @return void
   */
  public function delete(Profile $profile): void;
}
