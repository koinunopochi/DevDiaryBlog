<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProfileIconRepositoryInterface;

class GetAllDefaultProfileIconsUseCase
{
  private $profileIconRepository;

  public function __construct(ProfileIconRepositoryInterface $profileIconRepository)
  {
    $this->profileIconRepository = $profileIconRepository;
  }

  public function execute(): array
  {
    return $this->profileIconRepository->getDefaultAll();
  }
}
