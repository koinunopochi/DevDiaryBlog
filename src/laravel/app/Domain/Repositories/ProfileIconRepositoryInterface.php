<?php

namespace App\Domain\Repositories;

interface ProfileIconRepositoryInterface
{
  /**
   * Get all ProfileIcons
   *
   * @return array
   */
  public function getDefaultAll(): array;
}
