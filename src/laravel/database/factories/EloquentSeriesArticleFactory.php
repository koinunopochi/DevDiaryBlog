<?php

namespace Database\Factories;

use App\Models\EloquentSeriesArticle;
use App\Models\EloquentSeries;
use App\Models\EloquentArticle;
use App\Domain\ValueObjects\SeriesArticleId;
use App\Domain\ValueObjects\SeriesOrder;
use App\Domain\ValueObjects\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentSeriesArticleFactory extends Factory
{
  protected $model = EloquentSeriesArticle::class;

  public function definition(): array
  {
    return [
      'id' => (new SeriesArticleId())->toString(),
      'series_id' => $this->generateValidSeriesId(),
      'article_id' => $this->generateValidArticleId(),
      'order' => $this->generateValidSeriesOrder(),
      'created_at' => (new DateTime())->toString(),
      'updated_at' => (new DateTime())->toString(),
    ];
  }

  private function generateValidSeriesId(): string
  {
    return EloquentSeries::factory()->create()->id;
  }

  private function generateValidArticleId(): string
  {
    return EloquentArticle::factory()->create()->id;
  }

  private function generateValidSeriesOrder(): int
  {
    $order = $this->faker->unique()->numberBetween(1, 100);
    return (new SeriesOrder($order))->toInt();
  }

  public function forSeries(EloquentSeries $series): self
  {
    return $this->state(function (array $attributes) use ($series) {
      return [
        'series_id' => $series->id,
      ];
    });
  }

  public function forArticle(EloquentArticle $article): self
  {
    return $this->state(function (array $attributes) use ($article) {
      return [
        'article_id' => $article->id,
      ];
    });
  }
}
