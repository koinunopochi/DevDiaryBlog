<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\TagName;

class TagNameTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testValidTagName(): void
  {
    $validNames = ['タグ', 'Tag', 'タグ123', '日本語タグ', 'ＡＢＣ１２３'];
    foreach ($validNames as $name) {
      $tagName = new TagName($name);
      $this->assertEquals($name, $tagName->toString());
    }
  }

  /**
   * @test
   */
  public function testEmptyTagName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new TagName('');
  }

  /**
   * @test
   */
  public function testWhitespaceOnlyTagName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new TagName('   ');
  }

  /**
   * @test
   */
  public function testTooLongTagName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new TagName('この文字列は25文字を超えています1234567890');
  }

  /**
   * @test
   */
  public function testTagNameWithSymbols(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new TagName('タグ!@#$%');
  }

  /**
   * @test
   */
  public function testTagNameWithSpaces(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new TagName('タグ 名');
  }

  /**
   * @test
   */
  public function testEquality(): void
  {
    $tagName1 = new TagName('テストタグ');
    $tagName2 = new TagName('テストタグ');
    $tagName3 = new TagName('別のタグ');

    $this->assertTrue($tagName1->equals($tagName2));
    $this->assertFalse($tagName1->equals($tagName3));
  }
}
