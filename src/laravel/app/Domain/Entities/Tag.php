<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;

class Tag
{
  private TagId $id;
  private TagName $name;

  public function __construct(TagId $id, TagName $name)
  {
    $this->id = $id;
    $this->name = $name;
  }

  public function getId(): TagId
  {
    return $this->id;
  }

  public function getName(): TagName
  {
    return $this->name;
  }
}
