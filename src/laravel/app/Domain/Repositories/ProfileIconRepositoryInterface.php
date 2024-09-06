<?php

namespace App\Domain\Repositories;

use App\Domain\ValueObjects\S3FilePathCollection;

interface ProfileIconRepositoryInterface
{
  /**
   * Get all ProfileIcons
   *
   * @return S3FilePathCollection
   */
  public function getDefaultAll(): S3FilePathCollection;
}
