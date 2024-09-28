<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Series;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\SeriesTitle;
use App\Domain\ValueObjects\SeriesDescription;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SeriesStatus;
use App\Domain\ValueObjects\DateTime;
use App\Models\EloquentSeries;
use Illuminate\Support\Collection;
use App\Domain\Repositories\SeriesRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentSeriesRepository implements SeriesRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentSeries::all()->map(function (EloquentSeries $eloquentSeries) {
      return $this->mapToDomainEntity($eloquentSeries);
    });
  }

  public function findById(SeriesId $id): ?Series
  {
    $eloquentSeries = EloquentSeries::find($id->toString());
    return $eloquentSeries ? $this->mapToDomainEntity($eloquentSeries) : null;
  }

  public function findByAuthorId(UserId $authorId): Collection
  {
    return EloquentSeries::where('author_id', $authorId->toString())->get()->map(function (EloquentSeries $eloquentSeries) {
      return $this->mapToDomainEntity($eloquentSeries);
    });
  }

  public function save(Series $series): void
  {
    $eloquentSeries = EloquentSeries::findOrNew($series->getId()->toString());
    $eloquentSeries->id = $series->getId()->toString();
    $eloquentSeries->title = $series->getTitle()->toString();
    $eloquentSeries->description = $series->getDescription()->toString();
    $eloquentSeries->author_id = $series->getAuthorId()->toString();
    $eloquentSeries->status = $series->getStatus()->toString();
    $eloquentSeries->created_at = $series->getCreatedAt()->toString();
    $eloquentSeries->updated_at = $series->getUpdatedAt()->toString();
    $eloquentSeries->save();
  }

  public function delete(Series $series): void
  {
    EloquentSeries::destroy($series->getId()->toString());
  }

  public function getPaginated(
    int $page,
    int $perPage,
    array $filters = [],
    string $sortBy = 'created_at',
    string $sortDirection = 'desc'
  ): LengthAwarePaginator {
    $query = EloquentSeries::query();

    foreach ($filters as $key => $value) {
      $query->where($key, $value);
    }

    return $query->orderBy($sortBy, $sortDirection)
      ->paginate($perPage, ['*'], 'page', $page)
      ->through(function (EloquentSeries $eloquentSeries) {
        return $this->mapToDomainEntity($eloquentSeries);
      });
  }

  public function searchByTitle(SeriesTitle $title): Collection
  {
    return EloquentSeries::where('title', 'like', '%' . $title->toString() . '%')->get()->map(function (EloquentSeries $eloquentSeries) {
      return $this->mapToDomainEntity($eloquentSeries);
    });
  }

  public function findByStatus(SeriesStatus $status): Collection
  {
    return EloquentSeries::where('status', $status->toString())->get()->map(function (EloquentSeries $eloquentSeries) {
      return $this->mapToDomainEntity($eloquentSeries);
    });
  }

  public function countByAuthorId(UserId $authorId): int
  {
    return EloquentSeries::where('author_id', $authorId->toString())->count();
  }

  private function mapToDomainEntity(EloquentSeries $eloquentSeries): Series
  {
    return new Series(
      new SeriesId($eloquentSeries->id),
      new SeriesTitle($eloquentSeries->title),
      new SeriesDescription($eloquentSeries->description),
      new UserId($eloquentSeries->author_id),
      new SeriesStatus($eloquentSeries->status),
      new DateTime($eloquentSeries->created_at),
      new DateTime($eloquentSeries->updated_at)
    );
  }
}
