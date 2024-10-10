<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleTagNameCollection;
use App\Domain\ValueObjects\TagName;

class ArticleTagNameCollectionTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // config()->set('logging.default', 'stderr');
  }

  /** @test */
  public function testCreateInstance()
  {
    // Given
    $tagNames = [new TagName('PHP'), new TagName('Laravel')];

    // When
    $tagCollection = new ArticleTagNameCollection($tagNames);

    // Then
    $this->assertInstanceOf(ArticleTagNameCollection::class, $tagCollection);
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $tagNames = [new TagName('PHP'), new TagName('Laravel')];

    // When
    $tagCollection = new ArticleTagNameCollection($tagNames);

    // Then
    $this->assertEquals($tagNames, $tagCollection->toArray());
  }

  /** @test */
  public function testValidate_TooManyTags()
  {
    // Given
    $tagNames = array_map(fn($i) => new TagName("Tag$i"), range(1, 6));

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("タグの数が上限の5を超えています");
    new ArticleTagNameCollection($tagNames);
  }

  /** @test */
  public function testValidate_MaximumTagsAllowed()
  {
    // Given
    $tagNames = array_map(fn($i) => new TagName("Tag$i"), range(1, 5));

    // When
    $tagCollection = new ArticleTagNameCollection($tagNames);

    // Then
    $this->assertInstanceOf(ArticleTagNameCollection::class, $tagCollection);
    $this->assertEquals(5, $tagCollection->count());
  }

  /** @test */
  public function testValidate_InvalidTagNameType()
  {
    // Given
    $tagNames = [new TagName('PHP'), 'invalid_tag_name'];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("無効なTagNameタイプです: string");
    new ArticleTagNameCollection($tagNames);
  }

  /** @test */
  public function testValidate_DuplicateTagNames()
  {
    // Given
    $tagName = new TagName('PHP');
    $tagNames = [$tagName, $tagName];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("重複するTagNameが存在します");
    new ArticleTagNameCollection($tagNames);
  }

  /** @test */
  public function testCount()
  {
    // Given
    $tagNames = [new TagName('PHP'), new TagName('Laravel'), new TagName('Vue.js')];

    // When
    $tagCollection = new ArticleTagNameCollection($tagNames);

    // Then
    $this->assertEquals(3, $tagCollection->count());
  }

  /** @test */
  public function testEquals_SameCollection()
  {
    // Given
    $tagNames = [new TagName('PHP'), new TagName('Laravel')];
    $collection1 = new ArticleTagNameCollection($tagNames);
    $collection2 = new ArticleTagNameCollection($tagNames);

    // When & Then
    $this->assertTrue($collection1->equals($collection2));
  }

  /** @test */
  public function testEquals_DifferentCollection()
  {
    // Given
    $collection1 = new ArticleTagNameCollection([new TagName('PHP'), new TagName('Laravel')]);
    $collection2 = new ArticleTagNameCollection([new TagName('Vue.js'), new TagName('React')]);

    // When & Then
    $this->assertFalse($collection1->equals($collection2));
  }

  /** @test */
  public function testEquals_DifferentCount()
  {
    // Given
    $collection1 = new ArticleTagNameCollection([new TagName('PHP'), new TagName('Laravel')]);
    $collection2 = new ArticleTagNameCollection([new TagName('PHP')]);

    // When & Then
    $this->assertFalse($collection1->equals($collection2));
  }

  /** @test */
  public function testMap()
  {
    // Given
    $tagName1 = new TagName('PHP');
    $tagName2 = new TagName('Laravel');
    $tagNames = [$tagName1, $tagName2];
    $tagCollection = new ArticleTagNameCollection($tagNames);

    // When
    $result = $tagCollection->map(function (TagName $tagName) {
      return $tagName->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertCount(2, $result);
    $this->assertEquals($tagName1->toString(), $result[0]);
    $this->assertEquals($tagName2->toString(), $result[1]);
  }

  /** @test */
  public function testMap_WithEmptyCollection()
  {
    // Given
    $tagCollection = new ArticleTagNameCollection([]);

    // When
    $result = $tagCollection->map(function (TagName $tagName) {
      return $tagName->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertEmpty($result);
  }

  /** @test */
  public function testMap_WithCustomCallback()
  {
    // Given
    $tagName1 = new TagName('PHP');
    $tagName2 = new TagName('Laravel');
    $tagNames = [$tagName1, $tagName2];
    $tagCollection = new ArticleTagNameCollection($tagNames);

    // When
    $result = $tagCollection->map(function (TagName $tagName) {
      return 'Tag: ' . $tagName->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertCount(2, $result);
    $this->assertStringStartsWith('Tag: ', $result[0]);
    $this->assertStringStartsWith('Tag: ', $result[1]);
    $this->assertNotEquals($result[0], $result[1]);
  }
}
