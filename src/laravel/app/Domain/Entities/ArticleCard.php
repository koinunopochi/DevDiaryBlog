<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleAuthor;
use App\Domain\ValueObjects\Likes;
use App\Domain\ValueObjects\ArticleTagNameCollection;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\TagName;
use Illuminate\Support\Facades\Log;

class ArticleCard
{
  private ArticleId $id;
  private ArticleTitle $title;
  private ArticleAuthor $author;
  private Likes $likes;
  private ArticleTagNameCollection $tags;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(
    ArticleId $id,
    ArticleTitle $title,
    ArticleAuthor $author,
    Likes $likes,
    ArticleTagNameCollection $tags,
    DateTime $createdAt,
    DateTime $updatedAt
  ) {
    $this->id = $id;
    $this->title = $title;
    $this->author = $author;
    $this->likes = $likes;
    $this->tags = $tags;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;

    Log::info('class : ArticleList - method : constructor - ArticleId : ' . $this->id->toString());
  }

  public function getId(): ArticleId
  {
    return $this->id;
  }

  public function getTitle(): ArticleTitle
  {
    return $this->title;
  }

  public function getAuthor(): ArticleAuthor
  {
    return $this->author;
  }

  public function getLikes(): Likes
  {
    return $this->likes;
  }

  public function getTags(): ArticleTagNameCollection
  {
    return $this->tags;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id->toString(),
      'title' => $this->title->toString(),
      'author' => $this->author->toArray(),
      'likes' => $this->likes->getValue(),
      'tags' => $this->tags->map(fn(TagName $tagName)=> $tagName->toString()),
      'createdAt' => $this->createdAt->toString(),
      'updatedAt' => $this->updatedAt->toString()
    ];
  }
}
