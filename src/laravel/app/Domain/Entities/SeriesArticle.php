<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\SeriesArticleId;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\SeriesOrder;

class SeriesArticle
{
  private SeriesArticleId $id;
  private SeriesId $seriesId;
  private ArticleId $articleId;
  private SeriesOrder $order;

  public function __construct(
    SeriesArticleId $id,
    SeriesId $seriesId,
    ArticleId $articleId,
    SeriesOrder $order
  ) {
    $this->id = $id;
    $this->seriesId = $seriesId;
    $this->articleId = $articleId;
    $this->order = $order;
  }

  public function getId(): SeriesArticleId
  {
    return $this->id;
  }

  public function getSeriesId(): SeriesId
  {
    return $this->seriesId;
  }

  public function getArticleId(): ArticleId
  {
    return $this->articleId;
  }

  public function getOrder(): SeriesOrder
  {
    return $this->order;
  }

  public function updateOrder(SeriesOrder $order): self
  {
    return new self($this->id, $this->seriesId, $this->articleId, $order);
  }
}
