<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\SeriesArticle;
use App\Domain\ValueObjects\SeriesArticleId;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\SeriesOrder;
use Tests\TestCase;

class SeriesArticleTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $seriesArticleId = new SeriesArticleId();
    $seriesId = new SeriesId();
    $articleId = new ArticleId();
    $order = new SeriesOrder(1);

    // When
    $seriesArticle = new SeriesArticle($seriesArticleId, $seriesId, $articleId, $order);

    // Then
    $this->assertInstanceOf(SeriesArticle::class, $seriesArticle);
    $this->assertEquals($seriesArticleId, $seriesArticle->getId());
    $this->assertEquals($seriesId, $seriesArticle->getSeriesId());
    $this->assertEquals($articleId, $seriesArticle->getArticleId());
    $this->assertEquals($order, $seriesArticle->getOrder());
  }

  /** @test */
  public function testUpdateOrder()
  {
    // Given
    $originalSeriesArticle = new SeriesArticle(
      $seriesArticleId = new SeriesArticleId(),
      $seriesId = new SeriesId(),
      $articleId = new ArticleId(),
      $order = new SeriesOrder(1)
    );

    $newOrder = new SeriesOrder(2);

    // When
    $updatedSeriesArticle = $originalSeriesArticle->updateOrder($newOrder);

    // Then
    $this->assertNotSame($originalSeriesArticle, $updatedSeriesArticle);
    $this->assertEquals($newOrder, $updatedSeriesArticle->getOrder());

    // 元のSeriesArticleが変更されていないことを確認
    $this->assertNotEquals($newOrder, $originalSeriesArticle->getOrder());

    // その他の属性が維持されていることを確認
    $this->assertEquals($seriesArticleId, $updatedSeriesArticle->getId());
    $this->assertEquals($seriesId, $updatedSeriesArticle->getSeriesId());
    $this->assertEquals($articleId, $updatedSeriesArticle->getArticleId());
  }

  /** @test */
  public function testImmutability()
  {
    // Given
    $seriesArticle = new SeriesArticle(
      new SeriesArticleId(),
      new SeriesId(),
      new ArticleId(),
      new SeriesOrder(1)
    );

    // When
    $updatedSeriesArticle = $seriesArticle->updateOrder(new SeriesOrder(2));

    // Then
    $this->assertNotSame($seriesArticle, $updatedSeriesArticle);
  }
}
