<?php

namespace App\Application\UseCases;

use App\Application\DataTransferObjects\SaveProfileDTO;
use App\Domain\Repositories\UserProfileRepositoryInterface;

class SaveProfileUseCase
{
  private UserProfileRepositoryInterface $userProfileRepository;

  public function __construct(UserProfileRepositoryInterface $userProfileRepository)
  {
    $this->userProfileRepository = $userProfileRepository;
  }

  public function execute(SaveProfileDTO $saveProfileDTO)
  {
    $this->userProfileRepository->save($saveProfileDTO->toProfile());
  }
}
