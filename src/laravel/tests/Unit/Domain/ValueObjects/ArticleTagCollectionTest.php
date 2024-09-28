<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleTagCollection;
use App\Domain\ValueObjects\TagId;

class ArticleTagCollectionTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /** @test */
  public function testCreateInstance()
  {
    // Given
    $tagIds = [new TagId(), new TagId()];

    // When
    $tagCollection = new ArticleTagCollection($tagIds);

    // Then
    $this->assertInstanceOf(ArticleTagCollection::class, $tagCollection);
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $tagIds = [new TagId(), new TagId()];

    // When
    $tagCollection = new ArticleTagCollection($tagIds);

    // Then
    $this->assertEquals($tagIds, $tagCollection->toArray());
  }

  /** @test */
  public function testValidate_TooManyTags()
  {
    // Given
    $tagIds = array_fill(0, 6, new TagId());

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("タグの数が上限の5を超えています");
    new ArticleTagCollection($tagIds);
  }

  /** @test */
  public function testValidate_MaximumTagsAllowed()
  {
    // Given
    $tagIds = [
      new TagId(), // 1
      new TagId(), // 2
      new TagId(), // 3
      new TagId(), // 4
      new TagId(), // 5
    ];

    // When
    $tagCollection = new ArticleTagCollection($tagIds);

    // Then
    $this->assertInstanceOf(ArticleTagCollection::class, $tagCollection);
    $this->assertEquals(5, $tagCollection->count());
  }

  /** @test */
  public function testValidate_InvalidTagIdType()
  {
    // Given
    $tagIds = [new TagId(), 'invalid_tag_id'];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("無効なTagIdタイプです: string");
    new ArticleTagCollection($tagIds);
  }

  /** @test */
  public function testValidate_DuplicateTagIds()
  {
    // Given
    $tagId = new TagId();
    $tagIds = [$tagId, $tagId];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("重複するTagIdが存在します");
    new ArticleTagCollection($tagIds);
  }

  /** @test */
  public function testCount()
  {
    // Given
    $tagIds = [new TagId(), new TagId(), new TagId()];

    // When
    $tagCollection = new ArticleTagCollection($tagIds);

    // Then
    $this->assertEquals(3, $tagCollection->count());
  }

  /** @test */
  public function testEquals_SameCollection()
  {
    // Given
    $tagIds = [new TagId(), new TagId()];
    $collection1 = new ArticleTagCollection($tagIds);
    $collection2 = new ArticleTagCollection($tagIds);

    // When & Then
    $this->assertTrue($collection1->equals($collection2));
  }

  /** @test */
  public function testEquals_DifferentCollection()
  {
    // Given
    $collection1 = new ArticleTagCollection([new TagId(), new TagId()]);
    $collection2 = new ArticleTagCollection([new TagId(), new TagId()]);

    // When & Then
    $this->assertFalse($collection1->equals($collection2));
  }

  /** @test */
  public function testEquals_DifferentCount()
  {
    // Given
    $collection1 = new ArticleTagCollection([new TagId(), new TagId()]);
    $collection2 = new ArticleTagCollection([new TagId()]);

    // When & Then
    $this->assertFalse($collection1->equals($collection2));
  }

  /** @test */
  public function testMap()
  {
    // Given
    $tagId1 = new TagId();
    $tagId2 = new TagId();
    $tagIds = [$tagId1, $tagId2];
    $tagCollection = new ArticleTagCollection($tagIds);

    // When
    $result = $tagCollection->map(function (TagId $tagId) {
      return $tagId->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertCount(2, $result);
    $this->assertEquals($tagId1->toString(), $result[0]);
    $this->assertEquals($tagId2->toString(), $result[1]);
  }

  /** @test */
  public function testMap_WithEmptyCollection()
  {
    // Given
    $tagCollection = new ArticleTagCollection([]);

    // When
    $result = $tagCollection->map(function (TagId $tagId) {
      return $tagId->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertEmpty($result);
  }

  /** @test */
  public function testMap_WithCustomCallback()
  {
    // Given
    $tagId1 = new TagId();
    $tagId2 = new TagId();
    $tagIds = [$tagId1, $tagId2];
    $tagCollection = new ArticleTagCollection($tagIds);

    // When
    $result = $tagCollection->map(function (TagId $tagId) {
      return 'Tag: ' . $tagId->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertCount(2, $result);
    $this->assertStringStartsWith('Tag: ', $result[0]);
    $this->assertStringStartsWith('Tag: ', $result[1]);
    $this->assertNotEquals($result[0], $result[1]);
  }
}
