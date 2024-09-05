<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
  /**
   * Get all Users
   *
   * @return Collection<int, User>
   */
  public function all(): Collection;

  /**
   * Find a User by id
   *
   * @param UserId $id
   * @return User|null
   */
  public function findById(UserId $id): ?User;

  /**
   * Find a User by email
   *
   * @param Email $email
   * @return User|null
   */
  public function findByEmail(Email $email): ?User;

  /**
   * Find a User by name
   *
   * @param Username $name
   * @return User|null
   */
  public function findByName(Username $name): ?User;

  /**
   * Save a User
   *
   * @param User $user
   * @return void
   */
  public function save(User $user): void;

  /**
   * Delete a User
   *
   * @param User $user
   * @return void
   */
  public function delete(User $user): void;
}
