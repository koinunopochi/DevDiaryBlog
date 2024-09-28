<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\SeriesArticle;
use App\Domain\ValueObjects\SeriesArticleId;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\SeriesOrder;
use App\Models\EloquentSeriesArticle;
use Illuminate\Support\Collection;
use App\Domain\Repositories\SeriesArticleRepositoryInterface;

class EloquentSeriesArticleRepository implements SeriesArticleRepositoryInterface
{
  public function findById(SeriesArticleId $id): ?SeriesArticle
  {
    $eloquentSeriesArticle = EloquentSeriesArticle::find($id->toString());
    return $eloquentSeriesArticle ? $this->mapToDomainEntity($eloquentSeriesArticle) : null;
  }

  public function findBySeriesId(SeriesId $seriesId): Collection
  {
    return EloquentSeriesArticle::where('series_id', $seriesId->toString())
      ->orderBy('order')
      ->get()
      ->map(function (EloquentSeriesArticle $eloquentSeriesArticle) {
        return $this->mapToDomainEntity($eloquentSeriesArticle);
      });
  }

  public function findByArticleId(ArticleId $articleId): Collection
  {
    return EloquentSeriesArticle::where('article_id', $articleId->toString())
      ->get()
      ->map(function (EloquentSeriesArticle $eloquentSeriesArticle) {
        return $this->mapToDomainEntity($eloquentSeriesArticle);
      });
  }

  public function save(SeriesArticle $seriesArticle): void
  {
    $eloquentSeriesArticle = EloquentSeriesArticle::findOrNew($seriesArticle->getId()->toString());
    $eloquentSeriesArticle->id = $seriesArticle->getId()->toString();
    $eloquentSeriesArticle->series_id = $seriesArticle->getSeriesId()->toString();
    $eloquentSeriesArticle->article_id = $seriesArticle->getArticleId()->toString();
    $eloquentSeriesArticle->order = $seriesArticle->getOrder()->toInt();
    $eloquentSeriesArticle->save();
  }

  public function delete(SeriesArticle $seriesArticle): void
  {
    EloquentSeriesArticle::destroy($seriesArticle->getId()->toString());
  }

  public function findBySeriesIdAndArticleId(SeriesId $seriesId, ArticleId $articleId): ?SeriesArticle
  {
    $eloquentSeriesArticle = EloquentSeriesArticle::where('series_id', $seriesId->toString())
      ->where('article_id', $articleId->toString())
      ->first();
    return $eloquentSeriesArticle ? $this->mapToDomainEntity($eloquentSeriesArticle) : null;
  }

  public function findBySeriesIdAndOrder(SeriesId $seriesId, SeriesOrder $order): ?SeriesArticle
  {
    $eloquentSeriesArticle = EloquentSeriesArticle::where('series_id', $seriesId->toString())
      ->where('order', $order->toInt())
      ->first();
    return $eloquentSeriesArticle ? $this->mapToDomainEntity($eloquentSeriesArticle) : null;
  }

  public function countBySeriesId(SeriesId $seriesId): int
  {
    return EloquentSeriesArticle::where('series_id', $seriesId->toString())->count();
  }

  public function updateOrders(SeriesId $seriesId, array $newOrders): void
  {
    foreach ($newOrders as $articleId => $order) {
      EloquentSeriesArticle::where('series_id', $seriesId->toString())
        ->where('article_id', $articleId)
        ->update(['order' => $order->toInt()]);
    }
  }

  private function mapToDomainEntity(EloquentSeriesArticle $eloquentSeriesArticle): SeriesArticle
  {
    return new SeriesArticle(
      new SeriesArticleId($eloquentSeriesArticle->id),
      new SeriesId($eloquentSeriesArticle->series_id),
      new ArticleId($eloquentSeriesArticle->article_id),
      new SeriesOrder($eloquentSeriesArticle->order)
    );
  }
}
