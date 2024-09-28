<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\SeriesArticle;
use App\Domain\ValueObjects\SeriesArticleId;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\SeriesOrder;
use App\Infrastructure\Persistence\EloquentSeriesArticleRepository;
use App\Models\EloquentSeriesArticle;
use App\Models\EloquentSeries;
use App\Models\EloquentArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentSeriesArticleRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentSeriesArticleRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentSeriesArticleRepository();
  }

  public function test_findById(): void
  {
    // Given
    $createdSeriesArticle = EloquentSeriesArticle::factory()->create();

    // When
    $seriesArticle = $this->repository->findById(new SeriesArticleId($createdSeriesArticle->id));

    // Then
    $this->assertNotNull($seriesArticle);
    $this->assertEquals($createdSeriesArticle->id, $seriesArticle->getId()->toString());
    $this->assertEquals($createdSeriesArticle->series_id, $seriesArticle->getSeriesId()->toString());
    $this->assertEquals($createdSeriesArticle->article_id, $seriesArticle->getArticleId()->toString());
    $this->assertEquals($createdSeriesArticle->order, $seriesArticle->getOrder()->toInt());
  }

  public function test_findBySeriesId(): void
  {
    // Given
    $series = EloquentSeries::factory()->create();
    EloquentSeriesArticle::factory()->count(3)->create(['series_id' => $series->id]);
    EloquentSeriesArticle::factory()->count(2)->create();

    // When
    $seriesArticles = $this->repository->findBySeriesId(new SeriesId($series->id));

    // Then
    $this->assertCount(3, $seriesArticles);
    $this->assertContainsOnlyInstancesOf(SeriesArticle::class, $seriesArticles);
    foreach ($seriesArticles as $seriesArticle) {
      $this->assertEquals($series->id, $seriesArticle->getSeriesId()->toString());
    }
  }

  public function test_findByArticleId(): void
  {
    // Given
    $article = EloquentArticle::factory()->create();
    EloquentSeriesArticle::factory()->count(2)->create(['article_id' => $article->id]);
    EloquentSeriesArticle::factory()->count(3)->create();

    // When
    $seriesArticles = $this->repository->findByArticleId(new ArticleId($article->id));

    // Then
    $this->assertCount(2, $seriesArticles);
    $this->assertContainsOnlyInstancesOf(SeriesArticle::class, $seriesArticles);
    foreach ($seriesArticles as $seriesArticle) {
      $this->assertEquals($article->id, $seriesArticle->getArticleId()->toString());
    }
  }

  public function test_save_create_new(): void
  {
    // Given
    $series = EloquentSeries::factory()->create();
    $article = EloquentArticle::factory()->create();
    $newSeriesArticle = new SeriesArticle(
      new SeriesArticleId(),
      new SeriesId($series->id),
      new ArticleId($article->id),
      new SeriesOrder(1)
    );

    // When
    $this->repository->save($newSeriesArticle);

    // Then
    $seriesArticleFromDatabase = EloquentSeriesArticle::find($newSeriesArticle->getId()->toString());

    $this->assertNotNull($seriesArticleFromDatabase);
    $this->assertEquals($newSeriesArticle->getId()->toString(), $seriesArticleFromDatabase->id);
    $this->assertEquals($newSeriesArticle->getSeriesId()->toString(), $seriesArticleFromDatabase->series_id);
    $this->assertEquals($newSeriesArticle->getArticleId()->toString(), $seriesArticleFromDatabase->article_id);
    $this->assertEquals($newSeriesArticle->getOrder()->toInt(), $seriesArticleFromDatabase->order);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingSeriesArticle = EloquentSeriesArticle::factory()->create();

    // When
    $updatedSeriesArticle = new SeriesArticle(
      new SeriesArticleId($existingSeriesArticle->id),
      new SeriesId($existingSeriesArticle->series_id),
      new ArticleId($existingSeriesArticle->article_id),
      new SeriesOrder(5)
    );

    $this->repository->save($updatedSeriesArticle);

    // Then
    $seriesArticleFromDatabase = EloquentSeriesArticle::find($existingSeriesArticle->id);

    $this->assertNotNull($seriesArticleFromDatabase);
    $this->assertEquals($updatedSeriesArticle->getId()->toString(), $seriesArticleFromDatabase->id);
    $this->assertEquals($updatedSeriesArticle->getOrder()->toInt(), $seriesArticleFromDatabase->order);
  }

  public function test_delete(): void
  {
    // Given
    $seriesArticleToDelete = EloquentSeriesArticle::factory()->create();

    // When
    $seriesArticle = $this->repository->findById(new SeriesArticleId($seriesArticleToDelete->id));
    $this->assertNotNull($seriesArticle);

    $this->repository->delete($seriesArticle);

    // Then
    $this->assertDatabaseMissing('series_articles', [
      'id' => $seriesArticleToDelete->id
    ]);
  }

  public function test_findBySeriesIdAndArticleId(): void
  {
    // Given
    $seriesArticle = EloquentSeriesArticle::factory()->create();
    EloquentSeriesArticle::factory()->count(2)->create();

    // When
    $foundSeriesArticle = $this->repository->findBySeriesIdAndArticleId(
      new SeriesId($seriesArticle->series_id),
      new ArticleId($seriesArticle->article_id)
    );

    // Then
    $this->assertNotNull($foundSeriesArticle);
    $this->assertEquals($seriesArticle->id, $foundSeriesArticle->getId()->toString());
  }

  public function test_findBySeriesIdAndOrder(): void
  {
    // Given
    $seriesArticle = EloquentSeriesArticle::factory()->create();
    EloquentSeriesArticle::factory()->count(2)->create();

    // When
    $foundSeriesArticle = $this->repository->findBySeriesIdAndOrder(
      new SeriesId($seriesArticle->series_id),
      new SeriesOrder($seriesArticle->order)
    );

    // Then
    $this->assertNotNull($foundSeriesArticle);
    $this->assertEquals($seriesArticle->id, $foundSeriesArticle->getId()->toString());
  }

  public function test_countBySeriesId(): void
  {
    // Given
    $series = EloquentSeries::factory()->create();
    EloquentSeriesArticle::factory()->count(3)->create(['series_id' => $series->id]);
    EloquentSeriesArticle::factory()->count(2)->create();

    // When
    $count = $this->repository->countBySeriesId(new SeriesId($series->id));

    // Then
    $this->assertEquals(3, $count);
  }

  public function test_updateOrders(): void
  {
    // Given
    $series = EloquentSeries::factory()->create();
    $seriesArticles = EloquentSeriesArticle::factory()->count(3)->create(['series_id' => $series->id]);

    $newOrders = [
      $seriesArticles[0]->article_id => new SeriesOrder(3),
      $seriesArticles[1]->article_id => new SeriesOrder(1),
      $seriesArticles[2]->article_id => new SeriesOrder(2),
    ];

    // When
    $this->repository->updateOrders(new SeriesId($series->id), $newOrders);

    // Then
    foreach ($newOrders as $articleId => $order) {
      $updatedSeriesArticle = EloquentSeriesArticle::where('series_id', $series->id)
        ->where('article_id', $articleId)
        ->first();

      $this->assertNotNull($updatedSeriesArticle);
      $this->assertEquals($order->toInt(), $updatedSeriesArticle->order);
    }
  }
}
