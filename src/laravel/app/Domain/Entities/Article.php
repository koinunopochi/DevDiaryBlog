<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleTagCollection;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;

class Article
{
  private ArticleId $id;
  private ArticleTitle $title;
  private ArticleContent $content;
  private UserId $authorId;
  private ArticleCategoryId $categoryId;
  private ArticleTagCollection $tags;
  private ArticleStatus $status;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(
    ArticleId $id,
    ArticleTitle $title,
    ArticleContent $content,
    UserId $authorId,
    ArticleCategoryId $categoryId,
    ArticleTagCollection $tags,
    ArticleStatus $status,
    DateTime $createdAt,
    DateTime $updatedAt
  ) {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->authorId = $authorId;
    $this->categoryId = $categoryId;
    $this->tags = $tags;
    $this->status = $status;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getId(): ArticleId
  {
    return $this->id;
  }

  public function getTitle(): ArticleTitle
  {
    return $this->title;
  }

  public function getContent(): ArticleContent
  {
    return $this->content;
  }

  public function getAuthorId(): UserId
  {
    return $this->authorId;
  }

  public function getCategoryId(): ArticleCategoryId
  {
    return $this->categoryId;
  }

  public function getTags(): ArticleTagCollection
  {
    return $this->tags;
  }

  public function getStatus(): ArticleStatus
  {
    return $this->status;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  public function updateTitle(ArticleTitle $title, DateTime $updatedAt): self
  {
    return new self($this->id, $title, $this->content, $this->authorId, $this->categoryId, $this->tags, $this->status, $this->createdAt, $updatedAt);
  }

  public function updateContent(ArticleContent $content, DateTime $updatedAt): self
  {
    return new self($this->id, $this->title, $content, $this->authorId, $this->categoryId, $this->tags, $this->status, $this->createdAt, $updatedAt);
  }

  public function updateCategory(ArticleCategoryId $categoryId, DateTime $updatedAt): self
  {
    return new self($this->id, $this->title, $this->content, $this->authorId, $categoryId, $this->tags, $this->status, $this->createdAt, $updatedAt);
  }

  public function updateTags(ArticleTagCollection $tags, DateTime $updatedAt): self
  {
    return new self($this->id, $this->title, $this->content, $this->authorId, $this->categoryId, $tags, $this->status, $this->createdAt, $updatedAt);
  }

  public function updateStatus(ArticleStatus $status, DateTime $updatedAt): self
  {
    return new self($this->id, $this->title, $this->content, $this->authorId, $this->categoryId, $this->tags, $status, $this->createdAt, $updatedAt);
  }

  public function updateAll(
    ArticleTitle $title,
    ArticleContent $content,
    ArticleCategoryId $categoryId,
    ArticleTagCollection $tags,
    ArticleStatus $status,
    DateTime $updatedAt
  ): self {
    return new self($this->id, $title, $content, $this->authorId, $categoryId, $tags, $status, $this->createdAt, $updatedAt);
  }
}
