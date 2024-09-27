<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleCategoryName;
use App\Domain\ValueObjects\ArticleCategoryDescription;
use App\Domain\ValueObjects\ArticleCategoryTagCollection;

class ArticleCategory
{
  private ArticleCategoryId $id;
  private ArticleCategoryName $name;
  private ArticleCategoryDescription $description;
  private ArticleCategoryTagCollection $tags;

  public function __construct(
    ArticleCategoryId $id,
    ArticleCategoryName $name,
    ArticleCategoryDescription $description,
    ArticleCategoryTagCollection $tags
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->tags = $tags;
  }

  public function getId(): ArticleCategoryId
  {
    return $this->id;
  }

  public function getName(): ArticleCategoryName
  {
    return $this->name;
  }

  public function getDescription(): ArticleCategoryDescription
  {
    return $this->description;
  }

  public function getTags(): ArticleCategoryTagCollection
  {
    return $this->tags;
  }
}
