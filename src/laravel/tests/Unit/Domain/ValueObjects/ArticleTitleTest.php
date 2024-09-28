<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\ArticleTitle;
use Tests\TestCase;

class ArticleTitleTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $title = '有効な記事タイトル';

    // When
    $articleTitleObject = new ArticleTitle($title);

    // Then
    $this->assertInstanceOf(ArticleTitle::class, $articleTitleObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $title = '有効な記事タイトル';

    // When
    $articleTitleObject = new ArticleTitle($title);

    // Then
    $this->assertEquals($title, $articleTitleObject->toString());
  }

  /**
   * @test
   */
  public function testMinimumValidLength(): void
  {
    // Given
    $title = 'a'; // 最小の有効な長さ（1文字）

    // When
    $articleTitleObject = new ArticleTitle($title);

    // Then
    $this->assertInstanceOf(ArticleTitle::class, $articleTitleObject);
  }

  /**
   * @test
   */
  public function testMaximumValidLength(): void
  {
    // Given
    $title = str_repeat('あ', 255); // 最大の有効な長さ

    // When
    $articleTitleObject = new ArticleTitle($title);

    // Then
    $this->assertInstanceOf(ArticleTitle::class, $articleTitleObject);
  }

  /**
   * @test
   */
  public function testEmptyTitle(): void
  {
    // Given
    $title = '';

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('記事タイトルは空にできません。');
    new ArticleTitle($title);
  }

  /**
   * @test
   */
  public function testExceedMaxLength(): void
  {
    // Given
    $title = str_repeat('a', 256); // 最大長を1文字超過

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('記事タイトルは255文字以下である必要があります。');
    new ArticleTitle($title);
  }
}
