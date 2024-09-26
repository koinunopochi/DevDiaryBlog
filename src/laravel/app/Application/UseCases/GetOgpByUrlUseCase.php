<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\OgpRepositoryInterface;
use App\Domain\ValueObjects\Ogp;
use App\Domain\ValueObjects\Url;

class GetOgpByUrlUseCase
{
  private OgpRepositoryInterface $repository;

  public function __construct(OgpRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function execute(Url $url): Ogp
  {
    return $this->repository->getByUrl(url: $url);
  }
}
