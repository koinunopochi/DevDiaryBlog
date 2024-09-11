<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Policy;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyName;
use Illuminate\Support\Collection;

interface PolicyRepositoryInterface
{
  /**
   * Get all Policies
   *
   * @return Collection<int, Policy>
   */
  public function all(): Collection;

  /**
   * Find a Policy by id
   *
   * @param PolicyId $id
   * @return Policy|null
   */
  public function findById(PolicyId $id): ?Policy;

  /**
   * Find a Policy by name
   *
   * @param PolicyName $name
   * @return Policy|null
   */
  public function findByName(PolicyName $name): ?Policy;

  /**
   * Save a Policy
   *
   * @param Policy $policy
   * @return void
   */
  public function save(Policy $policy): void;

  /**
   * Delete a Policy
   *
   * @param Policy $policy
   * @return void
   */
  public function delete(Policy $policy): void;

  /**
   * Get Policies by version
   *
   * @param string $version
   * @return Collection<int, Policy>
   */
  public function getByVersion(string $version): Collection;
}
