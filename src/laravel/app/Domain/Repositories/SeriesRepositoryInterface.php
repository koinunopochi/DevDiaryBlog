<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Series;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SeriesTitle;
use App\Domain\ValueObjects\SeriesStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SeriesRepositoryInterface
{
  /**
   * 全てのSeriesを取得
   *
   * @return Collection<int, Series>
   */
  public function all(): Collection;

  /**
   * IDによってSeriesを検索
   *
   * @param SeriesId $id
   * @return Series|null
   */
  public function findById(SeriesId $id): ?Series;

  /**
   * 著者IDによってSeriesを検索
   *
   * @param UserId $authorId
   * @return Collection<int, Series>
   */
  public function findByAuthorId(UserId $authorId): Collection;

  /**
   * Seriesを保存
   *
   * @param Series $series
   * @return void
   */
  public function save(Series $series): void;

  /**
   * Seriesを削除
   *
   * @param Series $series
   * @return void
   */
  public function delete(Series $series): void;

  /**
   * ページネーション、ソート、フィルタリングに対応したSeriesの取得
   *
   * @param int $page
   * @param int $perPage
   * @param array $filters
   * @param string $sortBy
   * @param string $sortDirection
   * @return LengthAwarePaginator
   */
  public function getPaginated(
    int $page,
    int $perPage,
    array $filters = [],
    string $sortBy = 'created_at',
    string $sortDirection = 'desc'
  ): LengthAwarePaginator;

  /**
   * タイトルによるSeriesの検索
   *
   * @param SeriesTitle $title
   * @return Collection<int, Series>
   */
  public function searchByTitle(SeriesTitle $title): Collection;

  /**
   * ステータスによるSeriesの検索
   *
   * @param SeriesStatus $status
   * @return Collection<int, Series>
   */
  public function findByStatus(SeriesStatus $status): Collection;

  /**
   * 著者IDによるSeriesの数を取得
   *
   * @param UserId $authorId
   * @return int
   */
  public function countByAuthorId(UserId $authorId): int;
}
