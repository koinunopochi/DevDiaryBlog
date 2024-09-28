<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\ArticleContent;
use Tests\TestCase;

class ArticleContentTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $content = 'これは有効な記事の内容です。';

    // When
    $articleContentObject = new ArticleContent($content);

    // Then
    $this->assertInstanceOf(ArticleContent::class, $articleContentObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $content = 'これは有効な記事の内容です。';

    // When
    $articleContentObject = new ArticleContent($content);

    // Then
    $this->assertEquals($content, $articleContentObject->toString());
  }

  /**
   * @test
   */
  public function testMinimumValidLength(): void
  {
    // Given
    $content = 'a'; // 最小の有効な長さ（1文字）

    // When
    $articleContentObject = new ArticleContent($content);

    // Then
    $this->assertInstanceOf(ArticleContent::class, $articleContentObject);
  }

  /**
   * @test
   */
  public function testMaximumValidLength(): void
  {
    // Given
    $content = str_repeat('あ', 50000); // 最大の有効な長さ

    // When
    $articleContentObject = new ArticleContent($content);

    // Then
    $this->assertInstanceOf(ArticleContent::class, $articleContentObject);
  }

  /**
   * @test
   */
  public function testEmptyContent(): void
  {
    // Given
    $content = '';

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('記事内容は空にできません。');
    new ArticleContent($content);
  }

  /**
   * @test
   */
  public function testExceedMaxLength(): void
  {
    // Given
    $content = str_repeat('a', 50001); // 最大長を1文字超過

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('記事内容は50000文字以下である必要があります。');
    new ArticleContent($content);
  }
}
