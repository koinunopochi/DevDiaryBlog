<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Profile;
use App\Domain\Repositories\UserProfileRepositoryInterface;
use App\Domain\ValueObjects\UserId;

class FindProfileByUserIdUseCase
{
  private UserProfileRepositoryInterface $userProfileRepository;

  public function __construct(UserProfileRepositoryInterface $userProfileRepository)
  {
    $this->userProfileRepository = $userProfileRepository;
  }

  public function execute(UserId $userId): ?Profile
  {
    return $this->userProfileRepository->findById($userId);
  }
}
