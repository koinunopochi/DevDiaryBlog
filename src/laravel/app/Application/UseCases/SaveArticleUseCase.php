<?php

namespace App\Application\UseCases;

use App\Application\DataTransferObjects\SaveArticleDTO;
use App\Domain\Repositories\ArticleRepositoryInterface;

class SaveArticleUseCase
{
  private ArticleRepositoryInterface $articleRepository;

  public function __construct(ArticleRepositoryInterface $articleRepository)
  {
    $this->articleRepository = $articleRepository;
  }

  public function execute(SaveArticleDTO $saveArticleDTO): void
  {
    $article = $saveArticleDTO->toArticle();
    $this->articleRepository->save($article);
  }
}
