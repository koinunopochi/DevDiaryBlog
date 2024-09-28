<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CommentContent;
use App\Domain\ValueObjects\DateTime;
use App\Models\EloquentComment;
use Illuminate\Support\Collection;
use App\Domain\Repositories\CommentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCommentRepository implements CommentRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentComment::all()->map(function (EloquentComment $eloquentComment) {
      return $this->mapToDomainEntity($eloquentComment);
    });
  }

  public function findById(CommentId $id): ?Comment
  {
    $eloquentComment = EloquentComment::find($id->toString());
    return $eloquentComment ? $this->mapToDomainEntity($eloquentComment) : null;
  }

  public function findByArticleId(ArticleId $articleId): Collection
  {
    return EloquentComment::where('article_id', $articleId->toString())->get()->map(function (EloquentComment $eloquentComment) {
      return $this->mapToDomainEntity($eloquentComment);
    });
  }

  public function findByAuthorId(UserId $authorId): Collection
  {
    return EloquentComment::where('author_id', $authorId->toString())->get()->map(function (EloquentComment $eloquentComment) {
      return $this->mapToDomainEntity($eloquentComment);
    });
  }

  public function save(Comment $comment): void
  {
    $eloquentComment = EloquentComment::findOrNew($comment->getId()->toString());
    $eloquentComment->id = $comment->getId()->toString();
    $eloquentComment->article_id = $comment->getArticleId()->toString();
    $eloquentComment->author_id = $comment->getAuthorId()->toString();
    $eloquentComment->content = $comment->getContent()->toString();
    $eloquentComment->created_at = $comment->getCreatedAt()->toString();
    $eloquentComment->updated_at = $comment->getUpdatedAt()->toString();
    $eloquentComment->save();
  }

  public function delete(Comment $comment): void
  {
    EloquentComment::destroy($comment->getId()->toString());
  }

  public function getPaginated(
    int $page,
    int $perPage,
    array $filters = [],
    string $sortBy = 'created_at',
    string $sortDirection = 'desc'
  ): LengthAwarePaginator {
    $query = EloquentComment::query();

    foreach ($filters as $key => $value) {
      $query->where($key, $value);
    }

    return $query->orderBy($sortBy, $sortDirection)
      ->paginate($perPage, ['*'], 'page', $page)
      ->through(function (EloquentComment $eloquentComment) {
        return $this->mapToDomainEntity($eloquentComment);
      });
  }

  public function countByArticleId(ArticleId $articleId): int
  {
    return EloquentComment::where('article_id', $articleId->toString())->count();
  }

  public function searchByContent(CommentContent $content): Collection
  {
    return EloquentComment::where('content', 'like', '%' . $content->toString() . '%')->get()->map(function (EloquentComment $eloquentComment) {
      return $this->mapToDomainEntity($eloquentComment);
    });
  }

  private function mapToDomainEntity(EloquentComment $eloquentComment): Comment
  {
    return new Comment(
      new CommentId($eloquentComment->id),
      new ArticleId($eloquentComment->article_id),
      new UserId($eloquentComment->author_id),
      new CommentContent($eloquentComment->content),
      new DateTime($eloquentComment->created_at),
      new DateTime($eloquentComment->updated_at)
    );
  }
}
