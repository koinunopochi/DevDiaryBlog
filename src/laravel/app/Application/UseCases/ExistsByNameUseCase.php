<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\Username;

class ExistsByNameUseCase
{
  private UserRepositoryInterface $userRepository;

  public function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
  }
  public function execute(Username $username): bool
  {
    return $this->userRepository->existsByName($username);
  }
}
