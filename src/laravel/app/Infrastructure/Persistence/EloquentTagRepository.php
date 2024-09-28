<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Tag;
use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;
use App\Models\EloquentTag;
use Illuminate\Support\Collection;
use App\Domain\Repositories\TagRepositoryInterface;

class EloquentTagRepository implements TagRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentTag::all()->map(function (EloquentTag $eloquentTag) {
      return new Tag(
        new TagId($eloquentTag->id),
        new TagName($eloquentTag->name)
      );
    });
  }

  public function findById(TagId $id): ?Tag
  {
    $eloquentTag = EloquentTag::find($id->toString());

    return $eloquentTag ? new Tag(
      new TagId($eloquentTag->id),
      new TagName($eloquentTag->name)
    ) : null;
  }

  public function findByName(TagName $name): ?Tag
  {
    $eloquentTag = EloquentTag::where('name', $name->toString())->first();

    return $eloquentTag ? new Tag(
      new TagId($eloquentTag->id),
      new TagName($eloquentTag->name)
    ) : null;
  }

  public function save(Tag $tag): void
  {
    $eloquentTag = EloquentTag::findOrNew($tag->getId()->toString());
    $eloquentTag->id = $tag->getId()->toString();
    $eloquentTag->name = $tag->getName()->toString();
    $eloquentTag->save();
  }

  public function delete(Tag $tag): void
  {
    EloquentTag::destroy($tag->getId()->toString());
  }

  public function existsByName(TagName $name): bool
  {
    return EloquentTag::where('name', $name->toString())->exists();
  }
}
