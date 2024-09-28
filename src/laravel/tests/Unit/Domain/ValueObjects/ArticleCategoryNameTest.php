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
    $validNames = ['カテゴリ', 'Category', 'カテゴリ123', '日本語カテゴリ', 'ＡＢＣ１２３', 'カテゴリ!@#$%', 'カテゴリ 名', 'Category-Name', 'カテゴリ（）', 'Category&Name'];
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
  public function testCategoryNameWithMixedCharacters(): void
  {
    $validNames = [
      'カテゴリ123ABC',
      'ＡＢＣ１２３あいう',
      '日本語とEnglish',
      'ＡＢＣａｂｃ１２３',
      'カテゴリ!@#$%&*()_+-=[]{}|;:,.<>?',
      'Category Name with Spaces',
      'ハイフン-アンダースコア_記号！＠＃＄％'
    ];
    foreach ($validNames as $name) {
      $categoryName = new ArticleCategoryName($name);
      $this->assertEquals($name, $categoryName->toString());
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
