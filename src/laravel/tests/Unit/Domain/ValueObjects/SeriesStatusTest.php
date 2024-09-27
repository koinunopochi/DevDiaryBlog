<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\SeriesStatus;

class SeriesStatusTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $status = 'Draft';

    // When
    $seriesStatus = new SeriesStatus($status);

    // Then
    $this->assertInstanceOf(SeriesStatus::class, $seriesStatus);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $status = SeriesStatus::STATUS_DRAFT;
    $seriesStatus = new SeriesStatus($status);

    // When
    $result = $seriesStatus->toString();

    // Then
    $this->assertEquals($status, $result);
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusDraft(): void
  {
    // Given
    $status = SeriesStatus::STATUS_DRAFT;

    // When
    $seriesStatus = new SeriesStatus($status);

    // Then
    $this->assertInstanceOf(SeriesStatus::class, $seriesStatus);
    $this->assertTrue($seriesStatus->isDraft());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusPublished(): void
  {
    // Given
    $status = SeriesStatus::STATUS_PUBLISHED;

    // When
    $seriesStatus = new SeriesStatus($status);

    // Then
    $this->assertInstanceOf(SeriesStatus::class, $seriesStatus);
    $this->assertTrue($seriesStatus->isPublished());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusArchived(): void
  {
    // Given
    $status = SeriesStatus::STATUS_ARCHIVED;

    // When
    $seriesStatus = new SeriesStatus($status);

    // Then
    $this->assertInstanceOf(SeriesStatus::class, $seriesStatus);
    $this->assertTrue($seriesStatus->isArchived());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusDeleted(): void
  {
    // Given
    $status = SeriesStatus::STATUS_DELETED;

    // When
    $seriesStatus = new SeriesStatus($status);

    // Then
    $this->assertInstanceOf(SeriesStatus::class, $seriesStatus);
    $this->assertTrue($seriesStatus->isDeleted());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithInvalidStatus(): void
  {
    // Given
    $status = 'InvalidStatus';

    // Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('シリーズのステータスが不正です: InvalidStatus');

    // When
    new SeriesStatus($status);
  }

  /**
   * @test
   */
  public function testStatusCheckMethods(): void
  {
    // Given
    $draftStatus = new SeriesStatus(SeriesStatus::STATUS_DRAFT);
    $publishedStatus = new SeriesStatus(SeriesStatus::STATUS_PUBLISHED);
    $archivedStatus = new SeriesStatus(SeriesStatus::STATUS_ARCHIVED);
    $deletedStatus = new SeriesStatus(SeriesStatus::STATUS_DELETED);

    // Then
    $this->assertTrue($draftStatus->isDraft());
    $this->assertFalse($draftStatus->isPublished());
    $this->assertFalse($draftStatus->isArchived());
    $this->assertFalse($draftStatus->isDeleted());

    $this->assertFalse($publishedStatus->isDraft());
    $this->assertTrue($publishedStatus->isPublished());
    $this->assertFalse($publishedStatus->isArchived());
    $this->assertFalse($publishedStatus->isDeleted());

    $this->assertFalse($archivedStatus->isDraft());
    $this->assertFalse($archivedStatus->isPublished());
    $this->assertTrue($archivedStatus->isArchived());
    $this->assertFalse($archivedStatus->isDeleted());

    $this->assertFalse($deletedStatus->isDraft());
    $this->assertFalse($deletedStatus->isPublished());
    $this->assertFalse($deletedStatus->isArchived());
    $this->assertTrue($deletedStatus->isDeleted());
  }
}
