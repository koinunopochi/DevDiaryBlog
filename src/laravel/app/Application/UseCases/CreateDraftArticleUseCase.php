<?php

namespace App\Application\UseCases;

use App\Domain\Entities\DraftArticle;
use App\Domain\Repositories\ArticleRepositoryInterface;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;

class CreateDraftArticleUseCase
{
  private ArticleRepositoryInterface $articleRepository;

  public function __construct(ArticleRepositoryInterface $articleRepository)
  {
    $this->articleRepository = $articleRepository;
  }

  public function execute(): DraftArticle
  {
    $articleId = new ArticleId();
    $status = new ArticleStatus(ArticleStatus::STATUS_DRAFT);
    $createdAt = new DateTime();
    $draftArticle = new DraftArticle($articleId, $status, $createdAt);

    $this->articleRepository->reserveDraftArticle($draftArticle);

    return $draftArticle;
  }
}
