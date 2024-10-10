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

  private function applyCursor(Builder $query, ?Cursor $cursor, string $sortBy): Builder
  {
    if ($cursor) {
      $cursorData = $cursor->jsonSerialize();
      $query->where(function ($q) use ($cursorData, $sortBy) {
        $q->where($sortBy, $cursorData[$sortBy])
          ->where('id', '>', $cursorData['id']);
      })->orWhere($sortBy, '>', $cursorData[$sortBy]);
    }
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
    $hasNextPage = $articles->count() > $limit;
    $articles = $articles->take($limit);
    $lastArticle = $articles->last();
    $nextCursor = $lastArticle ? new Cursor(new ArticleId($lastArticle->id), new DateTime($lastArticle->created_at), new DateTime($lastArticle->updated_at)) : null;

    return [
      'data' => $articles->map(fn($article) => $this->mapToDomainEntity($article)),
      'nextCursor' => $nextCursor,
      'hasNextPage' => $hasNextPage
    ];
  }

  public function getArticleCards(int $limit, ?Cursor $cursor = null, array $filters = [], string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags']);
    $query = $this->applyFiltersAndSort($query, $filters, $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy);

    $articles = $query->take($limit + 1)->get();
    $result = $this->buildResult($articles, $limit);
    $result['totalItems'] = $this->getTotalCount($filters);

    return $result;
  }

  public function getByAuthorId(UserId $authorId, int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])->where('author_id', $authorId->toString());
    $query = $this->applyFiltersAndSort($query, [], $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function getByTag(TagName $tagName, int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
      ->whereHas('tags', function ($q) use ($tagName) {
        $q->where('name', $tagName->toString());
      });
    $query = $this->applyFiltersAndSort($query, [], $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function searchByTitle(string $searchTerm, int $limit, ?Cursor $cursor = null, string $sortBy = 'created_at', string $sortDirection = 'desc'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
      ->where('title', 'like', "%{$searchTerm}%");
    $query = $this->applyFiltersAndSort($query, [], $sortBy, $sortDirection);
    $query = $this->applyCursor($query, $cursor, $sortBy);

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
    $query = $this->applyCursor($query, $cursor, $sortBy);

    $articles = $query->take($limit + 1)->get();
    return $this->buildResult($articles, $limit);
  }

  public function getAfterDate(DateTime $date, int $limit, ?Cursor $cursor = null, string $dateField = 'created_at'): array
  {
    $query = EloquentArticle::with(['author', 'tags'])
      ->where($dateField, '>', $date->toString())
      ->orderBy($dateField, 'asc');
    $query = $this->applyCursor($query, $cursor, $dateField);

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
