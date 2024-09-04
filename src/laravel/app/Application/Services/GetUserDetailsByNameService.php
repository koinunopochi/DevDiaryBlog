<?php

namespace App\Application\Services;

use App\Application\UseCases\FindProfileByUserIdUseCase;
use App\Application\UseCases\FindUserByNameUseCase;
use App\Domain\Entities\UserDetails;
use App\Domain\ValueObjects\Username;

class GetUserDetailsByNameService
{
  private FindUserByNameUseCase $findUserByNameUseCase;
  private FindProfileByUserIdUseCase $findProfileByUserIdUseCase;

  public function __construct(FindUserByNameUseCase $findUserByNameUseCase, FindProfileByUserIdUseCase $findProfileByUserIdUseCase)
  {
    $this->findUserByNameUseCase = $findUserByNameUseCase;
    $this->findProfileByUserIdUseCase = $findProfileByUserIdUseCase;
  }

  public function execute(Username $username): ?UserDetails
  {
    $user = $this->findUserByNameUseCase->execute($username);

    if (!$user) {
      return null;
    }

    $profile = $this->findProfileByUserIdUseCase->execute($user->getUserId());

    return new UserDetails($user, $profile);
  }
}
