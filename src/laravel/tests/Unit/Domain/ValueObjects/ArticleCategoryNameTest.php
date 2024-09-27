<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleCategoryName;

class ArticleCategoryNameTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testValidCategoryName(): void
  {
    $validNames = ['カテゴリ', 'Category', 'カテゴリ123', '日本語カテゴリ', 'ＡＢＣ１２３'];
    foreach ($validNames as $name) {
      $categoryName = new ArticleCategoryName($name);
      $this->assertEquals($name, $categoryName->toString());
    }
  }

  /**
   * @test
   */
  public function testEmptyCategoryName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryName('');
  }

  /**
   * @test
   */
  public function testWhitespaceOnlyCategoryName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryName('   ');
  }

  /**
   * @test
   */
  public function testMaxLengthCategoryName(): void
  {
    $maxLengthName = str_repeat('あ', 50);
    $categoryName = new ArticleCategoryName($maxLengthName);
    $this->assertEquals($maxLengthName, $categoryName->toString());
  }

  /**
   * @test
   */
  public function testTooLongCategoryName(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryName(str_repeat('あ', 51));
  }

  /**
   * @test
   */
  public function testCategoryNameWithSymbols(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryName('カテゴリ!@#$%');
  }

  /**
   * @test
   */
  public function testCategoryNameWithSpaces(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryName('カテゴリ 名');
  }

  /**
   * @test
   */
  public function testCategoryNameWithMixedCharacters(): void
  {
    $validNames = [
      'カテゴリ123ABC',
      'ＡＢＣ１２３あいう',
      '日本語とEnglish',
      'ＡＢＣａｂｃ１２３'
    ];
    foreach ($validNames as $name) {
      $categoryName = new ArticleCategoryName($name);
      $this->assertEquals($name, $categoryName->toString());
    }
  }

  /**
   * @test
   */
  public function testCategoryNameWithInvalidCharacters(): void
  {
    $invalidNames = [
      'Category Name',  // スペースを含む
      'カテゴリ_名',     // アンダースコアを含む
      'Category-Name',  // ハイフンを含む
      'カテゴリ（）',    // 括弧を含む
      'Category&Name'   // アンパサンドを含む
    ];
    foreach ($invalidNames as $name) {
      $this->expectException(\InvalidArgumentException::class);
      new ArticleCategoryName($name);
    }
  }

  /**
   * @test
   */
  public function testEquality(): void
  {
    $categoryName1 = new ArticleCategoryName('テストカテゴリ');
    $categoryName2 = new ArticleCategoryName('テストカテゴリ');
    $categoryName3 = new ArticleCategoryName('別のカテゴリ');

    $this->assertTrue($categoryName1->equals($categoryName2));
    $this->assertFalse($categoryName1->equals($categoryName3));
  }
}
