<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Article;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\ArticleTagCollection;
use App\Models\EloquentArticle;
use App\Models\EloquentTag;
use Illuminate\Support\Collection;
use App\Domain\Repositories\ArticleRepositoryInterface;
use App\Domain\ValueObjects\TagId;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentArticle::with('tags')->get()->map(function (EloquentArticle $eloquentArticle) {
      return $this->mapToDomainEntity($eloquentArticle);
    });
  }

  public function findById(ArticleId $id): ?Article
  {
    $eloquentArticle = EloquentArticle::with('tags')->find($id->toString());

    return $eloquentArticle ? $this->mapToDomainEntity($eloquentArticle) : null;
  }

  public function findByAuthorId(UserId $authorId): Collection
  {
    return EloquentArticle::with('tags')->where('author_id', $authorId->toString())->get()->map(function (EloquentArticle $eloquentArticle) {
      return $this->mapToDomainEntity($eloquentArticle);
    });
  }

  public function findByCategoryId(ArticleCategoryId $categoryId): Collection
  {
    return EloquentArticle::with('tags')->where('category_id', $categoryId->toString())->get()->map(function (EloquentArticle $eloquentArticle) {
      return $this->mapToDomainEntity($eloquentArticle);
    });
  }

  public function findByStatus(ArticleStatus $status): Collection
  {
    return EloquentArticle::with('tags')->where('status', $status->toString())->get()->map(function (EloquentArticle $eloquentArticle) {
      return $this->mapToDomainEntity($eloquentArticle);
    });
  }

  public function searchByTitle(ArticleTitle $title): Collection
  {
    return EloquentArticle::with('tags')->where('title', 'like', '%' . $title->toString() . '%')->get()->map(function (EloquentArticle $eloquentArticle) {
      return $this->mapToDomainEntity($eloquentArticle);
    });
  }

  public function save(Article $article): void
  {
    $eloquentArticle = EloquentArticle::findOrNew($article->getId()->toString());
    $eloquentArticle->id = $article->getId()->toString();
    $eloquentArticle->title = $article->getTitle()->toString();
    $eloquentArticle->content = $article->getContent()->toString();
    $eloquentArticle->author_id = $article->getAuthorId()->toString();
    $eloquentArticle->category_id = $article->getCategoryId()->toString();
    $eloquentArticle->status = $article->getStatus()->toString();
    $eloquentArticle->created_at = $article->getCreatedAt()->toString();
    $eloquentArticle->updated_at = $article->getUpdatedAt()->toString();
    $eloquentArticle->save();

    $tagIds = $article->getTags()->map(function (TagId $tagId) {
      return $tagId->toString();
    });
    $eloquentArticle->tags()->sync($tagIds);
  }

  public function delete(Article $article): void
  {
    EloquentArticle::destroy($article->getId()->toString());
  }

  public function getPaginated(
    int $page,
    int $perPage,
    array $filters = [],
    string $sortBy = 'created_at',
    string $sortDirection = 'desc'
  ): LengthAwarePaginator {
    $query = EloquentArticle::with('tags');

    foreach ($filters as $key => $value) {
      $query->where($key, $value);
    }

    return $query->orderBy($sortBy, $sortDirection)
      ->paginate($perPage, ['*'], 'page', $page)
      ->through(function (EloquentArticle $eloquentArticle) {
        return $this->mapToDomainEntity($eloquentArticle);
      });
  }

  public function countByStatus(ArticleStatus $status): int
  {
    return EloquentArticle::where('status', $status->toString())->count();
  }

  public function findByTagId(string $tagId): Collection
  {
    return EloquentArticle::whereHas('tags', function ($query) use ($tagId) {
      $query->where('id', $tagId);
    })->get()->map(function (EloquentArticle $eloquentArticle) {
      return $this->mapToDomainEntity($eloquentArticle);
    });
  }

  private function mapToDomainEntity(EloquentArticle $eloquentArticle): Article
  {
    $tagIds = $eloquentArticle->tags->map(function (EloquentTag $eloquentTag) {
      return new TagId($eloquentTag->id);
    })->toArray();

    return new Article(
      new ArticleId($eloquentArticle->id),
      new ArticleTitle($eloquentArticle->title),
      new ArticleContent($eloquentArticle->content),
      new UserId($eloquentArticle->author_id),
      new ArticleCategoryId($eloquentArticle->category_id),
      new ArticleTagCollection($tagIds),
      new ArticleStatus($eloquentArticle->status),
      new DateTime($eloquentArticle->created_at),
      new DateTime($eloquentArticle->updated_at)
    );
  }
}
