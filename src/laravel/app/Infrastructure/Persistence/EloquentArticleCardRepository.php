<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\ArticleCard;
use App\Domain\Repositories\ArticleCardListRepositoryInterface;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleAuthor;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\Likes;
use App\Domain\ValueObjects\ArticleTagNameCollection;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\TagName;
use App\Domain\ValueObjects\Cursor;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\Username;
use App\Models\EloquentArticle;
use App\Models\EloquentProfile;
use App\Models\User as EloquentUser;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class EloquentArticleCardRepository implements ArticleCardListRepositoryInterface
{
  private function applyFiltersAndSort(Builder $query, array $filters, string $sortBy, string $sortDirection): Builder
  {
    foreach ($filters as $key => $value) {
      $query->where($key, $value);
    }
    return $query->orderBy($sortBy, $sortDirection);
  }


  private function applyCursor(Builder $query, ?Cursor $cursor, string $sortBy, string $sortDirection, int $limit): Builder
  {
    Log::debug('Entering applyCursor method', [
      'sortBy' => $sortBy,
      'sortDirection' => $sortDirection,
      'limit' => $limit
    ]);

    $query->select('id', 'title', 'author_id', 'category_id', 'status', 'created_at', 'updated_at');

    if ($cursor) {
      $cursorData = $cursor->jsonSerialize();
      // 日時形式を変換
      $cursorData['created_at'] = Carbon::parse($cursorData['created_at'])->format('Y-m-d H:i:s');
      $cursorData['updated_at'] = Carbon::parse($cursorData['updated_at'])->format('Y-m-d H:i:s');

      Log::debug('Cursor data', ['cursorData' => $cursorData]);

      $operator = $sortDirection === 'desc' ? '<' : '>';

      $query->where(function ($q) use ($cursorData, $operator) {
        $q->where('created_at', $operator, $cursorData['created_at'])
        ->orWhere(function ($subQ) use ($cursorData, $operator) {
          $subQ->where('created_at', '=', $cursorData['created_at'])
          ->where('updated_at', $operator, $cursorData['updated_at']);
        })
          ->orWhere(function ($subQ) use ($cursorData, $operator) {
            $subQ->where('created_at', '=', $cursorData['created_at'])
            ->where('updated_at', '=', $cursorData['updated_at'])
            ->where('id', $operator, $cursorData['id']);
          });
      });
    } else {
      Log::debug('No cursor provided');
    }

    $query->orderBy('created_at', $sortDirection)
      ->orderBy('updated_at', $sortDirection)
      ->orderBy('id', $sortDirection)
      ->limit($limit + 1);

    // バインディングを適用した実行可能なSQLクエリを生成
    $sql = $query->toSql();
    $bindings = $query->getBindings();
    $executableSql = vsprintf(str_replace(['?'], ['\'%s\''], $sql), $bindings);

    Log::debug('Executable SQL query', ['sql' => $executableSql]);

    return $query;
  }

  private function mapToDomainEntity(EloquentArticle $eloquentArticle): ArticleCard
  {
    $user = EloquentUser::find($eloquentArticle->author_id);
    $profile = EloquentProfile::where('user_id', $eloquentArticle->author_id)->first();
    Log::debug($user->name);
    Log::debug($profile->display_name);
    Log::debug($profile->avatar_url);

    return new ArticleCard(
      new ArticleId($eloquentArticle->id),
      new ArticleTitle($eloquentArticle->title),
      new ArticleAuthor(
        new Username($user->name),
        new DisplayName($profile->display_name),
        new Url($profile->avatar_url)
      ),
      new Likes(0), // ライクスは存在しないので、仮で0を設定
      new ArticleTagNameCollection(
        $eloquentArticle->tags->map(fn($tag) => new TagName($tag->name))->toArray()
      ),
      new DateTime($eloquentArticle->created_at),
      new DateTime($eloquentArticle->updated_at)
    );
  }

  private function buildResult(Collection $articles, int $limit): array
  {
    Log::info('buildResult: Initial articles count: ' . $articles->count());
    Log::info('buildResult: Limit: ' . $limit);
    Log::info('buildResult: Initial articles:', $articles->toArray());

    $hasNextPage = $articles->count() > $limit;
    Log::info('buildResult: Has next page: ' . ($hasNextPage ? 'true' : 'false'));

    $articles = $articles->take(limit: $limit);
    Log::info('buildResult: Articles after take(): ' . $articles->count());

    Log::info('buildResult: Articles:', $articles->toArray());

    $lastArticle = $articles->last();
    if ($lastArticle) {
      Log::info('buildResult: Last article details', [
        'id' => $lastArticle->id,
        'created_at' => $lastArticle->created_at,
        'updated_at' => $lastArticle->updated_at
      ]);
    } else {
      Log::info('buildResult: No last article found');
    }

    $nextCursor = $lastArticle ? new Cursor(new ArticleId($lastArticle->id), new DateTime($lastArticle->created_at), new DateTime($lastArticle->updated_at)) : null;

    if ($nextCursor) {
      Log::info('buildResult: Next cursor details', [
        'articleId' => $nextCursor->getId()->toString(),
        'createdAt' => $nextCursor->getCreatedAt()->toString(),
        'updatedAt' => $nextCursor->getUpdatedAt()->toString()
      ]);
    } else {
      Log::info('buildResult: No next cursor created');
    }

    $result = [
      'data' => $articles->map(fn($article) => $this->mapToDomainEntity($article)),
      'nextCursor' => $nextCursor,
      'hasNextPage' => $hasNextPage
    ];

    Log::info('buildResult: Final result', [
      'dataCount' => count($result['data']),
      'hasNextCursor' => $result['nextCursor'] !== null,
      'hasNextPage' => $result['hasNextPage']
    ]);

    return $result;
  }

  public function getArticleCards(int $limit, ?Cursor $cursor = null, array $filters = [], string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags']);
    $query = $this->applyFiltersAndSort($query, $filters, $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy, $sortDirection, $limit);

    $articles = $query->take($limit + 1)->get();
    $result = $this->buildResult($articles, $limit);
    $result['totalItems'] = $this->getTotalCount($filters);

    return $result;
  }

  public function getByAuthorId(UserId $authorId, int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
    ->where('author_id', $authorId->toString())
      ->orderBy($sortBy, $sortDirection)
      ->orderBy('id', $sortDirection);
    $query = $this->applyFiltersAndSort($query, [], $sortBy, $sortDirection);

    if ($cursor) {
      Log::info('Applying cursor', [
        'articleId' => $cursor->getId()->toString(),
        'createdAt' => $cursor->getCreatedAt()->toString()
      ]);
      $query = $this->applyCursor($query, $cursor, $sortBy,$sortDirection, $limit);
    } else {
      Log::info('No cursor to apply');
    }

    Log::info('Final SQL Query: ' . $query->toSql());
    Log::info('Final SQL Bindings: ' . json_encode($query->getBindings()));

    $articles = $query->limit($limit + 1)->get();
    return $this->buildResult($articles, limit: $limit);
  }

  public function getByTag(TagName $tagName, int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
      ->whereHas('tags', function ($q) use ($tagName) {
        $q->where('name', $tagName->toString());
      });
    $query = $this->applyFiltersAndSort($query, [], $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy,$sortDirection, $limit);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function searchByTitle(string $searchTerm, int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
      ->where('title', 'like', "%{$searchTerm}%");
    $query = $this->applyFiltersAndSort($query, [], $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy,$sortDirection, $limit);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function getMostLiked(int $limit, ?Cursor $cursor = null): array
  {
    // ライクスが存在しないため、作成日時でソートする
    return $this->getLatest($limit, $cursor);
  }

  public function getLatest(int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])->orderBy($sortBy, 'desc');
    $query = $this->applyCursor($query, $cursor, $sortBy,'desc', $limit);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function getAfterDate(DateTime $date, int $limit, ?Cursor $cursor = null, string $dateField = 'created_at'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
      ->where($dateField, '>', $date->toString())
      ->orderBy($dateField, 'asc');
    $query = $this->applyCursor($query, $cursor, $dateField,'desc', $limit);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function getTotalCount(array $filters = []): int
  {
    $query = EloquentArticle::query();
    foreach ($filters as $key => $value) {
      $query->where($key, $value);
    }
    return $query->count();
  }
}
