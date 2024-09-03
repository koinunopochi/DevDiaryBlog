<?php

namespace App\Application\Services;

use App\Application\UseCases\FindProfileByUserIdUseCase;
use App\Application\UseCases\FindUserByIdUseCase;
use App\Domain\Entities\UserDetails;
use App\Domain\ValueObjects\UserId;

class GetUserDetailsService
{
  private FindUserByIdUseCase $findUserByIdUseCase;
  private FindProfileByUserIdUseCase $findProfileByUserIdUseCase;

  public function __construct(FindUserByIdUseCase $findUserByIdUseCase, FindProfileByUserIdUseCase $findProfileByUserIdUseCase)
  {
    $this->findUserByIdUseCase = $findUserByIdUseCase;
    $this->findProfileByUserIdUseCase = $findProfileByUserIdUseCase;
  }

  public function execute(UserId $userId): ?UserDetails
  {
    $user = $this->findUserByIdUseCase->execute($userId);

    if (!$user) {
      return null;
    }

    $profile = $this->findProfileByUserIdUseCase->execute($userId);

    return new UserDetails($user, $profile);
  }
}
