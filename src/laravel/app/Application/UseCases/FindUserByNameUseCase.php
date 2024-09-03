<?php

namespace App\Application\UseCases;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\Username;

class FindUserByNameUseCase
{
  private UserRepositoryInterface $userRepository;

  public function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function execute(Username $username): ?User
  {
    return $this->userRepository->findByName($username);
  }
}
