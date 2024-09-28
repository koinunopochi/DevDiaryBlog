<?php

namespace Database\Factories;

use App\Models\EloquentSeries;
use App\Models\User as EloquentUser;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\SeriesTitle;
use App\Domain\ValueObjects\SeriesDescription;
use App\Domain\ValueObjects\SeriesStatus;
use App\Domain\ValueObjects\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentSeriesFactory extends Factory
{
  protected $model = EloquentSeries::class;

  public function definition(): array
  {
    return [
      'id' => (new SeriesId())->toString(),
      'title' => $this->generateValidSeriesTitle(),
      'description' => $this->generateValidSeriesDescription(),
      'author_id' => EloquentUser::factory(),
      'status' => $this->generateValidSeriesStatus(),
      'created_at' => (new DateTime())->toString(),
      'updated_at' => (new DateTime())->toString(),
    ];
  }

  private function generateValidSeriesTitle(): string
  {
    $title = $this->faker->sentence(4);
    return (new SeriesTitle($title))->toString();
  }

  private function generateValidSeriesDescription(): string
  {
    $description = $this->faker->paragraph(2, true);
    return (new SeriesDescription($description))->toString();
  }

  private function generateValidSeriesStatus(): string
  {
    $statuses = ['Draft', 'Published', 'Archived', 'Deleted'];
    $status = $this->faker->randomElement($statuses);
    return (new SeriesStatus($status))->toString();
  }
}
