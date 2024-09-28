<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Tag;
use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;
use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
  /**
   * Get all Tags
   *
   * @return Collection<int, Tag>
   */
  public function all(): Collection;

  /**
   * Find a Tag by id
   *
   * @param TagId $id
   * @return Tag|null
   */
  public function findById(TagId $id): ?Tag;

  /**
   * Find a Tag by name
   *
   * @param TagName $name
   * @return Tag|null
   */
  public function findByName(TagName $name): ?Tag;

  /**
   * Save a Tag
   *
   * @param Tag $tag
   * @return void
   */
  public function save(Tag $tag): void;

  /**
   * Delete a Tag
   *
   * @param Tag $tag
   * @return void
   */
  public function delete(Tag $tag): void;

  /**
   * Determine if a Tag exists by name
   *
   * @param TagName $name
   * @return bool
   */
  public function existsByName(TagName $name): bool;
}
