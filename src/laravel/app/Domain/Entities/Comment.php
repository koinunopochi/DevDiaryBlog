<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CommentContent;
use App\Domain\ValueObjects\DateTime;

class Comment
{
  private CommentId $id;
  private ArticleId $articleId;
  private UserId $authorId;
  private CommentContent $content;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(
    CommentId $id,
    ArticleId $articleId,
    UserId $authorId,
    CommentContent $content,
    DateTime $createdAt,
    DateTime $updatedAt
  ) {
    $this->id = $id;
    $this->articleId = $articleId;
    $this->authorId = $authorId;
    $this->content = $content;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getId(): CommentId
  {
    return $this->id;
  }

  public function getArticleId(): ArticleId
  {
    return $this->articleId;
  }

  public function getAuthorId(): UserId
  {
    return $this->authorId;
  }

  public function getContent(): CommentContent
  {
    return $this->content;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  public function updateContent(CommentContent $content, DateTime $updatedAt): self
  {
    return new self($this->id, $this->articleId, $this->authorId, $content, $this->createdAt, $updatedAt);
  }
}
