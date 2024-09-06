<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProfileIconRepositoryInterface;
use App\Domain\ValueObjects\S3FilePathCollection;

class GetAllDefaultProfileIconsUseCase
{
  private $profileIconRepository;

  public function __construct(ProfileIconRepositoryInterface $profileIconRepository)
  {
    $this->profileIconRepository = $profileIconRepository;
  }

  public function execute(): S3FilePathCollection
  {
    return $this->profileIconRepository->getDefaultAll();
  }
}
