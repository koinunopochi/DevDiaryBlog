<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\SeriesArticle;
use App\Domain\ValueObjects\SeriesArticleId;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\SeriesOrder;
use Illuminate\Support\Collection;

interface SeriesArticleRepositoryInterface
{
  /**
   * IDによってSeriesArticleを検索
   *
   * @param SeriesArticleId $id
   * @return SeriesArticle|null
   */
  public function findById(SeriesArticleId $id): ?SeriesArticle;

  /**
   * SeriesIdによってSeriesArticleを検索
   *
   * @param SeriesId $seriesId
   * @return Collection<int, SeriesArticle>
   */
  public function findBySeriesId(SeriesId $seriesId): Collection;

  /**
   * ArticleIdによってSeriesArticleを検索
   *
   * @param ArticleId $articleId
   * @return Collection<int, SeriesArticle>
   */
  public function findByArticleId(ArticleId $articleId): Collection;

  /**
   * SeriesArticleを保存
   *
   * @param SeriesArticle $seriesArticle
   * @return void
   */
  public function save(SeriesArticle $seriesArticle): void;

  /**
   * SeriesArticleを削除
   *
   * @param SeriesArticle $seriesArticle
   * @return void
   */
  public function delete(SeriesArticle $seriesArticle): void;

  /**
   * SeriesIdとArticleIdの組み合わせでSeriesArticleを検索
   *
   * @param SeriesId $seriesId
   * @param ArticleId $articleId
   * @return SeriesArticle|null
   */
  public function findBySeriesIdAndArticleId(SeriesId $seriesId, ArticleId $articleId): ?SeriesArticle;

  /**
   * SeriesIdとOrderでSeriesArticleを検索
   *
   * @param SeriesId $seriesId
   * @param SeriesOrder $order
   * @return SeriesArticle|null
   */
  public function findBySeriesIdAndOrder(SeriesId $seriesId, SeriesOrder $order): ?SeriesArticle;

  /**
   * SeriesIdに属するSeriesArticleの数を取得
   *
   * @param SeriesId $seriesId
   * @return int
   */
  public function countBySeriesId(SeriesId $seriesId): int;

  /**
   * SeriesIdに属するSeriesArticleの順序を更新
   *
   * @param SeriesId $seriesId
   * @param array<ArticleId, SeriesOrder> $newOrders
   * @return void
   */
  public function updateOrders(SeriesId $seriesId, array $newOrders): void;
}
