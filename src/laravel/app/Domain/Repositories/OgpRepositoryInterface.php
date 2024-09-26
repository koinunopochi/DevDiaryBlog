<?php

namespace App\Domain\Repositories;

use App\Domain\ValueObjects\Ogp;
use App\Domain\ValueObjects\Url;

interface OgpRepositoryInterface
{
  public function getByUrl(Url $url): Ogp;
}
