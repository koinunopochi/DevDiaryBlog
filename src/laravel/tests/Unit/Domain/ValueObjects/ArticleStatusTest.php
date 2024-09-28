<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleStatus;

class ArticleStatusTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $status = 'Draft';

    // When
    $articleStatus = new ArticleStatus($status);

    // Then
    $this->assertInstanceOf(ArticleStatus::class, $articleStatus);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $status = ArticleStatus::STATUS_DRAFT;
    $articleStatus = new ArticleStatus($status);

    // When
    $result = $articleStatus->toString();

    // Then
    $this->assertEquals($status, $result);
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusDraft(): void
  {
    // Given
    $status = ArticleStatus::STATUS_DRAFT;

    // When
    $articleStatus = new ArticleStatus($status);

    // Then
    $this->assertInstanceOf(ArticleStatus::class, $articleStatus);
    $this->assertTrue($articleStatus->isDraft());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusPublished(): void
  {
    // Given
    $status = ArticleStatus::STATUS_PUBLISHED;

    // When
    $articleStatus = new ArticleStatus($status);

    // Then
    $this->assertInstanceOf(ArticleStatus::class, $articleStatus);
    $this->assertTrue($articleStatus->isPublished());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusArchived(): void
  {
    // Given
    $status = ArticleStatus::STATUS_ARCHIVED;

    // When
    $articleStatus = new ArticleStatus($status);

    // Then
    $this->assertInstanceOf(ArticleStatus::class, $articleStatus);
    $this->assertTrue($articleStatus->isArchived());
  }

  /**
   * @test
   */
  public function testCreateInstanceWithValidStatusDeleted(): void
  {
    // Given
    $status = ArticleStatus::STATUS_DELETED;

    // When
    $articleStatus = new ArticleStatus($status);

    // Then
    $this->assertInstanceOf(ArticleStatus::class, $articleStatus);
    $this->assertTrue($articleStatus->isDeleted());
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
    $this->expectExceptionMessage('記事のステータスが不正です: InvalidStatus');

    // When
    new ArticleStatus($status);
  }

  /**
   * @test
   */
  public function testStatusCheckMethods(): void
  {
    // Given
    $draftStatus = new ArticleStatus(ArticleStatus::STATUS_DRAFT);
    $publishedStatus = new ArticleStatus(ArticleStatus::STATUS_PUBLISHED);
    $archivedStatus = new ArticleStatus(ArticleStatus::STATUS_ARCHIVED);
    $deletedStatus = new ArticleStatus(ArticleStatus::STATUS_DELETED);

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
