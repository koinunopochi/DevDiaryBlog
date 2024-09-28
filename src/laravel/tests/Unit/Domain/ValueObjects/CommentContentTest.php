<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\CommentContent;
use Tests\TestCase;

class CommentContentTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $content = 'This is a valid comment.';

    // When
    $commentContentObject = new CommentContent($content);

    // Then
    $this->assertInstanceOf(CommentContent::class, $commentContentObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $content = 'This is a valid comment.';

    // When
    $commentContentObject = new CommentContent($content);

    // Then
    $this->assertEquals($content, $commentContentObject->toString());
  }

  /**
   * @test
   */
  public function testMinimumValidLength(): void
  {
    // Given
    $content = 'a'; // 最小の有効な長さ（1文字）

    // When
    $commentContentObject = new CommentContent($content);

    // Then
    $this->assertInstanceOf(CommentContent::class, $commentContentObject);
  }

  /**
   * @test
   */
  public function testMaximumValidLength(): void
  {
    // Given
    $content = str_repeat('あ', 1000); // 最大の有効な長さ

    // When
    $commentContentObject = new CommentContent($content);

    // Then
    $this->assertInstanceOf(CommentContent::class, $commentContentObject);
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
    $this->expectExceptionMessage('コメント内容は空にできません。');
    new CommentContent($content);
  }

  /**
   * @test
   */
  public function testExceedMaxLength(): void
  {
    // Given
    $content = str_repeat('a', 1001); // 最大長を1文字超過

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('コメント内容は1000文字以下である必要があります。');
    new CommentContent($content);
  }
}
