<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;

class GetOrCreateTagUseCase
{
  private TagRepositoryInterface $tagRepository;

  public function __construct(TagRepositoryInterface $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function execute(TagName $tagName): Tag
  {
    $tag = $this->tagRepository->findByName($tagName);
    if($tag instanceof Tag) return $tag;

    $new = new Tag(new TagId(),$tagName);
    $this->tagRepository->save($new);
    return $new;
  }
}
