<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Series;
use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\SeriesTitle;
use App\Domain\ValueObjects\SeriesDescription;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SeriesStatus;
use App\Domain\ValueObjects\DateTime;
use Tests\TestCase;

class SeriesTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $seriesId = new SeriesId();
    $title = new SeriesTitle('テストシリーズ');
    $description = new SeriesDescription('これはテストシリーズです。');
    $authorId = new UserId();
    $status = new SeriesStatus(SeriesStatus::STATUS_DRAFT);
    $createdAt = new DateTime();
    $updatedAt = new DateTime();

    // When
    $series = new Series($seriesId, $title, $description, $authorId, $status, $createdAt, $updatedAt);

    // Then
    $this->assertInstanceOf(Series::class, $series);
    $this->assertEquals($seriesId, $series->getId());
    $this->assertEquals($title, $series->getTitle());
    $this->assertEquals($description, $series->getDescription());
    $this->assertEquals($authorId, $series->getAuthorId());
    $this->assertEquals($status, $series->getStatus());
    $this->assertEquals($createdAt, $series->getCreatedAt());
    $this->assertEquals($updatedAt, $series->getUpdatedAt());
  }

  /** @test */
  public function testUpdateTitle()
  {
    // Given
    $originalSeries = new Series(
      $seriesId = new SeriesId(),
      $title = new SeriesTitle('元のタイトル'),
      $description = new SeriesDescription('説明'),
      $authorId = new UserId(),
      $status = new SeriesStatus(SeriesStatus::STATUS_DRAFT),
      $createdAt = new DateTime(),
      new DateTime()
    );

    $newTitle = new SeriesTitle('新しいタイトル');
    $newUpdatedAt = new DateTime();

    // When
    $updatedSeries = $originalSeries->updateTitle($newTitle, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalSeries, $updatedSeries);
    $this->assertEquals($newTitle, $updatedSeries->getTitle());
    $this->assertEquals($newUpdatedAt, $updatedSeries->getUpdatedAt());

    // 元のシリーズが変更されていないことを確認
    $this->assertNotEquals($newTitle, $originalSeries->getTitle());
    $this->assertNotEquals($newUpdatedAt, $originalSeries->getUpdatedAt());

    // その他の属性が維持されていることを確認
    $this->assertEquals($seriesId, $updatedSeries->getId());
    $this->assertEquals($description, $updatedSeries->getDescription());
    $this->assertEquals($authorId, $updatedSeries->getAuthorId());
    $this->assertEquals($status, $updatedSeries->getStatus());
    $this->assertEquals($createdAt, $updatedSeries->getCreatedAt());
  }

  /** @test */
  public function testUpdateDescription()
  {
    // Given
    $originalSeries = new Series(
      $seriesId = new SeriesId(),
      $title = new SeriesTitle('タイトル'),
      $description = new SeriesDescription('元の説明'),
      $authorId = new UserId(),
      $status = new SeriesStatus(SeriesStatus::STATUS_DRAFT),
      $createdAt = new DateTime(),
      new DateTime()
    );

    $newDescription = new SeriesDescription('新しい説明');
    $newUpdatedAt = new DateTime();

    // When
    $updatedSeries = $originalSeries->updateDescription($newDescription, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalSeries, $updatedSeries);
    $this->assertEquals($newDescription, $updatedSeries->getDescription());
    $this->assertEquals($newUpdatedAt, $updatedSeries->getUpdatedAt());

    // 元のシリーズが変更されていないことを確認
    $this->assertNotEquals($newDescription, $originalSeries->getDescription());
    $this->assertNotEquals($newUpdatedAt, $originalSeries->getUpdatedAt());

    // その他の属性が維持されていることを確認
    $this->assertEquals($seriesId, $updatedSeries->getId());
    $this->assertEquals($title, $updatedSeries->getTitle());
    $this->assertEquals($authorId, $updatedSeries->getAuthorId());
    $this->assertEquals($status, $updatedSeries->getStatus());
    $this->assertEquals($createdAt, $updatedSeries->getCreatedAt());
  }

  /** @test */
  public function testUpdateStatus()
  {
    // Given
    $originalSeries = new Series(
      $seriesId = new SeriesId(),
      $title = new SeriesTitle('タイトル'),
      $description = new SeriesDescription('説明'),
      $authorId = new UserId(),
      $status = new SeriesStatus(SeriesStatus::STATUS_DRAFT),
      $createdAt = new DateTime(),
      new DateTime()
    );

    $newStatus = new SeriesStatus(SeriesStatus::STATUS_PUBLISHED);
    $newUpdatedAt = new DateTime();

    // When
    $updatedSeries = $originalSeries->updateStatus($newStatus, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalSeries, $updatedSeries);
    $this->assertEquals($newStatus, $updatedSeries->getStatus());
    $this->assertEquals($newUpdatedAt, $updatedSeries->getUpdatedAt());

    // 元のシリーズが変更されていないことを確認
    $this->assertNotEquals($newStatus, $originalSeries->getStatus());
    $this->assertNotEquals($newUpdatedAt, $originalSeries->getUpdatedAt());

    // その他の属性が維持されていることを確認
    $this->assertEquals($seriesId, $updatedSeries->getId());
    $this->assertEquals($title, $updatedSeries->getTitle());
    $this->assertEquals($description, $updatedSeries->getDescription());
    $this->assertEquals($authorId, $updatedSeries->getAuthorId());
    $this->assertEquals($createdAt, $updatedSeries->getCreatedAt());
  }
}
