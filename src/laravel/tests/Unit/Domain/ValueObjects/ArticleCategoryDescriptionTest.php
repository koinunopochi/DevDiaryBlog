<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\ArticleCategoryDescription;
use Tests\TestCase;

class ArticleCategoryDescriptionTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $description = 'This is a category description.';

    // When
    $descriptionValueObject = new ArticleCategoryDescription($description);

    // Then
    $this->assertInstanceOf(ArticleCategoryDescription::class, $descriptionValueObject);
  }

  /**
   * @test
   */
  public function testToString(): void
  {
    // Given
    $description = 'This is a category description.';

    // When
    $descriptionValueObject = new ArticleCategoryDescription($description);

    // Then
    $this->assertEquals($description, $descriptionValueObject->toString());
  }

  /**
   * @test
   */
  public function testValidLength(): void
  {
    // Given
    $description = str_repeat('あ', 255);

    // When
    $descriptionValueObject = new ArticleCategoryDescription($description);

    // Then
    $this->assertInstanceOf(ArticleCategoryDescription::class, $descriptionValueObject);
  }

  /**
   * @test
   */
  public function testInvalidLength(): void
  {
    // Given
    $description = str_repeat('あ', 256);

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new ArticleCategoryDescription($description);
  }

  /**
   * @test
   */
  public function testEmptyString(): void
  {
    // Given
    $description = '';

    // When
    $descriptionValueObject = new ArticleCategoryDescription($description);

    // Then
    $this->assertInstanceOf(ArticleCategoryDescription::class, $descriptionValueObject);
    $this->assertEquals('', $descriptionValueObject->toString());
  }

  /**
   * @test
   */
  public function testSpecialCharacters(): void
  {
    // Given
    $description = 'Special characters: !@#$%^&*()_+{}[]|\\:;"\'<>,.?/~`';

    // When
    $descriptionValueObject = new ArticleCategoryDescription($description);

    // Then
    $this->assertInstanceOf(ArticleCategoryDescription::class, $descriptionValueObject);
    $this->assertEquals($description, $descriptionValueObject->toString());
  }

  /**
   * @test
   */
  public function testMultibyteCharacters(): void
  {
    // Given
    $description = '日本語の説明文です。これは255文字以内です。';

    // When
    $descriptionValueObject = new ArticleCategoryDescription($description);

    // Then
    $this->assertInstanceOf(ArticleCategoryDescription::class, $descriptionValueObject);
    $this->assertEquals($description, $descriptionValueObject->toString());
  }

  /**
   * @test
   */
  public function testEquality(): void
  {
    // Given
    $description1 = new ArticleCategoryDescription('Description 1');
    $description2 = new ArticleCategoryDescription('Description 1');
    $description3 = new ArticleCategoryDescription('Description 2');

    // When & Then
    $this->assertTrue($description1->equals($description2));
    $this->assertFalse($description1->equals($description3));
  }
}
