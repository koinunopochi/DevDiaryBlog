<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\ArticleCategory;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleCategoryName;
use App\Domain\ValueObjects\ArticleCategoryDescription;
use App\Domain\ValueObjects\ArticleCategoryTagCollection;
use App\Domain\ValueObjects\TagId;
use App\Models\EloquentArticleCategory;
use App\Models\EloquentTag;
use Illuminate\Support\Collection;
use App\Domain\Repositories\ArticleCategoryRepositoryInterface;

class EloquentArticleCategoryRepository implements ArticleCategoryRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentArticleCategory::with('tags')->get()->map(function (EloquentArticleCategory $eloquentArticleCategory) {
      return $this->toEntity($eloquentArticleCategory);
    });
  }

  public function findById(ArticleCategoryId $id): ?ArticleCategory
  {
    $eloquentArticleCategory = EloquentArticleCategory::with('tags')->find($id->toString());

    return $eloquentArticleCategory ? $this->toEntity($eloquentArticleCategory) : null;
  }

  public function findByName(ArticleCategoryName $name): ?ArticleCategory
  {
    $eloquentArticleCategory = EloquentArticleCategory::with('tags')->where('name', $name->toString())->first();

    return $eloquentArticleCategory ? $this->toEntity($eloquentArticleCategory) : null;
  }

  public function save(ArticleCategory $articleCategory): void
  {
    $eloquentArticleCategory = EloquentArticleCategory::findOrNew($articleCategory->getId()->toString());
    $eloquentArticleCategory->id = $articleCategory->getId()->toString();
    $eloquentArticleCategory->name = $articleCategory->getName()->toString();
    $eloquentArticleCategory->description = $articleCategory->getDescription()->toString();
    $eloquentArticleCategory->save();

    // タグの同期
    $tagIds = $articleCategory->getTags()->map(fn (TagId $tagId) => $tagId->toString());
    $eloquentArticleCategory->tags()->sync($tagIds);
  }

  public function delete(ArticleCategory $articleCategory): void
  {
    EloquentArticleCategory::destroy($articleCategory->getId()->toString());
  }

  public function existsByName(ArticleCategoryName $name): bool
  {
    return EloquentArticleCategory::where('name', $name->toString())->exists();
  }

  private function toEntity(EloquentArticleCategory $eloquentArticleCategory): ArticleCategory
  {
    $tagIds = $eloquentArticleCategory->tags->map(function (EloquentTag $eloquentTag) {
      return new TagId($eloquentTag->id);
    })->toArray();

    return new ArticleCategory(
      new ArticleCategoryId($eloquentArticleCategory->id),
      new ArticleCategoryName($eloquentArticleCategory->name),
      new ArticleCategoryDescription($eloquentArticleCategory->description),
      new ArticleCategoryTagCollection($tagIds)
    );
  }
}
