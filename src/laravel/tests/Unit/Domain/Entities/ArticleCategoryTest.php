<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleCategoryName;
use App\Domain\ValueObjects\ArticleCategoryDescription;
use App\Domain\ValueObjects\ArticleCategoryTagCollection;
use App\Domain\ValueObjects\TagId;
use Tests\TestCase;
use App\Domain\Entities\ArticleCategory;

class ArticleCategoryTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $categoryId = new ArticleCategoryId();
    $categoryName = new ArticleCategoryName('テストカテゴリ');
    $categoryDescription = new ArticleCategoryDescription('テスト用のカテゴリ説明');
    $categoryTags = new ArticleCategoryTagCollection([new TagId(), new TagId()]);

    // When
    $articleCategory = new ArticleCategory($categoryId, $categoryName, $categoryDescription, $categoryTags);

    // Then
    $this->assertInstanceOf(ArticleCategory::class, $articleCategory);
    $this->assertEquals($categoryId, $articleCategory->getId());
    $this->assertEquals($categoryName, $articleCategory->getName());
    $this->assertEquals($categoryDescription, $articleCategory->getDescription());
    $this->assertEquals($categoryTags, $articleCategory->getTags());
  }

  /** @test */
  public function testGetters()
  {
    // Given
    $categoryId = new ArticleCategoryId();
    $categoryName = new ArticleCategoryName('テストカテゴリ');
    $categoryDescription = new ArticleCategoryDescription('テスト用のカテゴリ説明');
    $categoryTags = new ArticleCategoryTagCollection([new TagId(), new TagId()]);

    $articleCategory = new ArticleCategory($categoryId, $categoryName, $categoryDescription, $categoryTags);

    // When & Then
    $this->assertInstanceOf(ArticleCategoryId::class, $articleCategory->getId());
    $this->assertInstanceOf(ArticleCategoryName::class, $articleCategory->getName());
    $this->assertInstanceOf(ArticleCategoryDescription::class, $articleCategory->getDescription());
    $this->assertInstanceOf(ArticleCategoryTagCollection::class, $articleCategory->getTags());

    $this->assertEquals($categoryId->toString(), $articleCategory->getId()->toString());
    $this->assertEquals($categoryName->toString(), $articleCategory->getName()->toString());
    $this->assertEquals($categoryDescription->toString(), $articleCategory->getDescription()->toString());
    $this->assertEquals($categoryTags->count(), $articleCategory->getTags()->count());
  }
}
