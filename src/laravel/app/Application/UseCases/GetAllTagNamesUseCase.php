<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use Illuminate\Support\Collection;

class GetAllTagNamesUseCase
{
  private $tagRepository;

  public function __construct(TagRepositoryInterface $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function execute(): Collection
  {
    $tags = $this->tagRepository->all();

    return $tags->map(function (Tag $tag): string {
      return $tag->getName()->toString();
    });
  }
}
