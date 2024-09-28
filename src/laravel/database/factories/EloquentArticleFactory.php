<?php

namespace Database\Factories;

use App\Models\EloquentArticle;
use App\Models\User as EloquentUser;
use App\Models\EloquentArticleCategory;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentArticleFactory extends Factory
{
  protected $model = EloquentArticle::class;

  public function definition(): array
  {
    return [
      'id' => (new ArticleId())->toString(),
      'title' => $this->generateValidArticleTitle(),
      'content' => $this->generateValidArticleContent(),
      'author_id' => EloquentUser::factory(),
      'category_id' => EloquentArticleCategory::factory(),
      'status' => $this->generateValidArticleStatus(),
      'created_at' => (new DateTime())->toString(),
      'updated_at' => (new DateTime())->toString(),
    ];
  }

  private function generateValidArticleTitle(): string
  {
    $title = $this->faker->sentence(6, true);
    return (new ArticleTitle($title))->toString();
  }

  private function generateValidArticleContent(): string
  {
    $content = $this->faker->paragraphs(3, true);
    return (new ArticleContent($content))->toString();
  }

  private function generateValidArticleStatus(): string
  {
    $statuses = [ArticleStatus::STATUS_DRAFT, ArticleStatus::STATUS_PUBLISHED, ArticleStatus::STATUS_ARCHIVED, ArticleStatus::STATUS_DELETED];
    $status = $this->faker->randomElement($statuses);
    return (new ArticleStatus($status))->toString();
  }
}
