<?php

namespace Database\Factories;

use App\Models\EloquentComment;
use App\Models\EloquentArticle;
use App\Models\User as EloquentUser;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\CommentContent;
use App\Domain\ValueObjects\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentCommentFactory extends Factory
{
  protected $model = EloquentComment::class;

  public function definition(): array
  {
    return [
      'id' => (new CommentId())->toString(),
      'article_id' => EloquentArticle::factory(),
      'author_id' => EloquentUser::factory(),
      'content' => $this->generateValidCommentContent(),
      'created_at' => (new DateTime())->toString(),
      'updated_at' => (new DateTime())->toString(),
    ];
  }

  private function generateValidCommentContent(): string
  {
    $content = $this->faker->paragraph(3, true);
    return (new CommentContent($content))->toString();
  }
}
