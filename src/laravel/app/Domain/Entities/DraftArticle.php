<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\ArticleTagIdCollection;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\UserId;

class DraftArticle
{
  private ArticleId $id;
  private ArticleStatus $status;
  private DateTime $createdAt;

  public function __construct(ArticleId $id, ArticleStatus $status, DateTime $createdAt)
  {
    $this->id = $id;
    $this->status = $status;
    $this->createdAt = $createdAt;
  }

  public function getId(): ArticleId
  {
    return $this->id;
  }

  public function getStatus(): ArticleStatus
  {
    return $this->status;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function toArticle(
    ArticleTitle $title,
    ArticleContent $content,
    UserId $authorId,
    ArticleCategoryId $categoryId,
    ArticleTagIdCollection $tags,
    DateTime $updatedAt
  ): Article {
    return new Article(
      $this->id,
      $title,
      $content,
      $authorId,
      $categoryId,
      $tags,
      $this->status,
      $this->createdAt,
      $updatedAt
    );
  }
}
