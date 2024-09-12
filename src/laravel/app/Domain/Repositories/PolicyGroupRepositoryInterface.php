<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\PolicyGroup;
use App\Domain\ValueObjects\PolicyGroupId;
use App\Domain\ValueObjects\PolicyGroupIdCollection;
use App\Domain\ValueObjects\PolicyGroupName;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyIdCollection;

interface PolicyGroupRepositoryInterface
{
  /**
   * Get all PolicyGroups
   *
   * @return PolicyGroupIdCollection
   */
  public function all(): PolicyGroupIdCollection;

  /**
   * Find a PolicyGroup by id
   *
   * @param PolicyGroupId $id
   * @return PolicyGroup|null
   */
  public function findById(PolicyGroupId $id): ?PolicyGroup;

  /**
   * Find a PolicyGroup by name
   *
   * @param PolicyGroupName $name
   * @return PolicyGroup|null
   */
  public function findByName(PolicyGroupName $name): ?PolicyGroup;

  /**
   * Save a PolicyGroup
   *
   * @param PolicyGroup $policyGroup
   * @return void
   */
  public function save(PolicyGroup $policyGroup): void;

  /**
   * Delete a PolicyGroup
   *
   * @param PolicyGroup $policyGroup
   * @return void
   */
  public function delete(PolicyGroup $policyGroup): void;

  /**
   * Get Policies associated with a PolicyGroup
   *
   * @param PolicyGroupId $id
   * @return PolicyIdCollection
   */
  public function getPolicies(PolicyGroupId $id): PolicyIdCollection;

  /**
   * Add a Policy to a PolicyGroup
   *
   * @param PolicyGroupId $groupId
   * @param PolicyId $policyId
   * @return void
   */
  public function addPolicy(PolicyGroupId $groupId, PolicyId $policyId): void;

  /**
   * Remove a Policy from a PolicyGroup
   *
   * @param PolicyGroupId $groupId
   * @param PolicyId $policyId
   * @return void
   */
  public function removePolicy(PolicyGroupId $groupId, PolicyId $policyId): void;
}
