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
    $validNames = [
      'タグ',
      'Tag',
      'タグ123',
      '日本語タグ',
      'ＡＢＣ１２３',
      'タグ!@#$%',
      'タグ 名',
      'C++',
      'C#',
      'ASP.NET',
      'Ruby on Rails',
      'Vue.js',
      'Node.js',
      'Machine Learning',
      'CI/CD',
      'AR/VR',
      '5G',
      'エッジコンピューティング'
    ];
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
  public function testMinimumLengthTagName(): void
  {
    $tagName = new TagName('a');
    $this->assertEquals('a', $tagName->toString());
  }

  /**
   * @test
   */
  public function testMaximumLengthTagName(): void
  {
    $maxLengthName = str_repeat('a', 25);
    $tagName = new TagName($maxLengthName);
    $this->assertEquals($maxLengthName, $tagName->toString());
  }

  /**
   * @test
   */
  public function testTooLongTagName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new TagName(str_repeat('a', 26));
  }

  /**
   * @test
   */
  public function testTagNameWithVariousCharacters(): void
  {
    $validNames = [
      'C++',
      'C#',
      '.NET',
      'Node.js',
      'Vue.js',
      'Machine Learning',
      'CI/CD',
      '5G',
      'AR/VR',
      'エッジコンピューティング',
      'Ruby on Rails',
      'タグ!@#$%^&*()',
      'Tag with spaces',
      '日本語 タグ 漢字',
      'Mix of あ123 and ABC'
    ];
    foreach ($validNames as $name) {
      $tagName = new TagName($name);
      $this->assertEquals($name, $tagName->toString());
    }
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

  /**
   * @test
   */
  public function testBoundaryValues(): void
  {
    // 1文字（最小長）
    $tagName = new TagName('a');
    $this->assertEquals('a', $tagName->toString());

    // 25文字（最大長）
    $maxLengthName = str_repeat('あ', 25);
    $tagName = new TagName($maxLengthName);
    $this->assertEquals($maxLengthName, $tagName->toString());

    // 0文字（無効）
    $this->expectException(\InvalidArgumentException::class);
    new TagName('');

    // 26文字（無効）
    $this->expectException(\InvalidArgumentException::class);
    new TagName(str_repeat('a', 26));
  }
}
