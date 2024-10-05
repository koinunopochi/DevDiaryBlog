<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Domain\ValueObjects\TagId;

class FindTagByIdUseCase
{
  private TagRepositoryInterface $tagRepository;

  public function __construct(TagRepositoryInterface $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function execute(TagId $tagId): ?Tag
  {
    return $this->tagRepository->findById($tagId);
  }
}
