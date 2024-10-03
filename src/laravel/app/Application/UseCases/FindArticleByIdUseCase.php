<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Article;
use App\Domain\Repositories\ArticleRepositoryInterface;
use App\Domain\ValueObjects\ArticleId;

class FindArticleByIdUseCase
{
  private ArticleRepositoryInterface $articleRepository;

  public function __construct(ArticleRepositoryInterface $articleRepository)
  {
    $this->articleRepository = $articleRepository;
  }

  public function execute(ArticleId $articleId): ?Article
  {
    return $this->articleRepository->findById($articleId);
  }
}
