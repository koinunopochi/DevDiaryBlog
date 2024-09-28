<?php

namespace Tests\Feature\Infrastructure\Persistence;

use App\Domain\Entities\Series;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\SeriesTitle;
use App\Domain\ValueObjects\SeriesDescription;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SeriesStatus;
use App\Domain\ValueObjects\DateTime;
use App\Infrastructure\Persistence\EloquentSeriesRepository;
use App\Models\EloquentSeries;
use App\Models\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentSeriesRepositoryTest extends TestCase
{
  use RefreshDatabase;

  private EloquentSeriesRepository $repository;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = new EloquentSeriesRepository();
  }

  public function test_all(): void
  {
    // Given
    EloquentSeries::factory()->count(3)->create();

    // When
    $series = $this->repository->all();

    // Then
    $this->assertCount(3, $series);
    $this->assertContainsOnlyInstancesOf(Series::class, $series);
  }

  public function test_findById(): void
  {
    // Given
    $createdSeries = EloquentSeries::factory()->create();

    // When
    $series = $this->repository->findById(new SeriesId($createdSeries->id));

    // Then
    $this->assertNotNull($series);
    $this->assertEquals($createdSeries->id, $series->getId()->toString());
    $this->assertEquals($createdSeries->title, $series->getTitle()->toString());
  }

  public function test_findByAuthorId(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    EloquentSeries::factory()->count(2)->create(['author_id' => $author->id]);
    EloquentSeries::factory()->count(3)->create();

    // When
    $series = $this->repository->findByAuthorId(new UserId($author->id));

    // Then
    $this->assertCount(2, $series);
    $this->assertContainsOnlyInstancesOf(Series::class, $series);
    foreach ($series as $seriesItem) {
      $this->assertEquals($author->id, $seriesItem->getAuthorId()->toString());
    }
  }

  public function test_save_create_new(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    $newSeries = new Series(
      new SeriesId(),
      new SeriesTitle('New Series'),
      new SeriesDescription('New Description'),
      new UserId($author->id),
      new SeriesStatus('Draft'),
      new DateTime(),
      new DateTime()
    );

    // When
    $this->repository->save($newSeries);

    // Then
    $seriesFromDatabase = EloquentSeries::find($newSeries->getId()->toString());

    $this->assertNotNull($seriesFromDatabase);
    $this->assertEquals($newSeries->getId()->toString(), $seriesFromDatabase->id);
    $this->assertEquals($newSeries->getTitle()->toString(), $seriesFromDatabase->title);
  }

  public function test_save_update_existing(): void
  {
    // Given
    $existingSeries = EloquentSeries::factory()->create();

    // When
    $updatedSeries = new Series(
      new SeriesId($existingSeries->id),
      new SeriesTitle('Updated Series'),
      new SeriesDescription($existingSeries->description),
      new UserId($existingSeries->author_id),
      new SeriesStatus($existingSeries->status),
      new DateTime($existingSeries->created_at),
      new DateTime()
    );

    $this->repository->save($updatedSeries);

    // Then
    $seriesFromDatabase = EloquentSeries::find($existingSeries->id);

    $this->assertNotNull($seriesFromDatabase);
    $this->assertEquals($updatedSeries->getId()->toString(), $seriesFromDatabase->id);
    $this->assertEquals($updatedSeries->getTitle()->toString(), $seriesFromDatabase->title);
  }

  public function test_delete(): void
  {
    // Given
    $seriesToDelete = EloquentSeries::factory()->create();

    // When
    $series = $this->repository->findById(new SeriesId($seriesToDelete->id));
    $this->assertNotNull($series);

    $this->repository->delete($series);

    // Then
    $this->assertDatabaseMissing('series', [
      'id' => $seriesToDelete->id
    ]);
  }

  public function test_getPaginated(): void
  {
    // Given
    EloquentSeries::factory()->count(20)->create();

    // When
    $paginatedSeries = $this->repository->getPaginated(1, 10);

    // Then
    $this->assertCount(10, $paginatedSeries->items());
    $this->assertEquals(20, $paginatedSeries->total());
    $this->assertEquals(2, $paginatedSeries->lastPage());
    $this->assertContainsOnlyInstancesOf(Series::class, $paginatedSeries->items());
  }

  public function test_searchByTitle(): void
  {
    // Given
    EloquentSeries::factory()->create(['title' => 'Test Series']);
    EloquentSeries::factory()->create(['title' => 'Another Series']);
    EloquentSeries::factory()->create(['title' => 'Something Else']);

    // When
    $series = $this->repository->searchByTitle(new SeriesTitle('Series'));

    // Then
    $this->assertCount(2, $series);
    $this->assertContainsOnlyInstancesOf(Series::class, $series);
    foreach ($series as $seriesItem) {
      $this->assertStringContainsString('Series', $seriesItem->getTitle()->toString());
    }
  }

  public function test_findByStatus(): void
  {
    // Given
    EloquentSeries::factory()->count(2)->create(['status' => 'Published']);
    EloquentSeries::factory()->count(3)->create(['status' => 'Draft']);

    // When
    $series = $this->repository->findByStatus(new SeriesStatus('Published'));

    // Then
    $this->assertCount(2, $series);
    $this->assertContainsOnlyInstancesOf(Series::class, $series);
    foreach ($series as $seriesItem) {
      $this->assertEquals('Published', $seriesItem->getStatus()->toString());
    }
  }

  public function test_countByAuthorId(): void
  {
    // Given
    $author = EloquentUser::factory()->create();
    EloquentSeries::factory()->count(3)->create(['author_id' => $author->id]);
    EloquentSeries::factory()->count(2)->create();

    // When
    $count = $this->repository->countByAuthorId(new UserId($author->id));

    // Then
    $this->assertEquals(3, $count);
  }
}
