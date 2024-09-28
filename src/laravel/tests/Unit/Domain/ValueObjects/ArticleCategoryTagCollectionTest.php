<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleCategoryTagCollection;
use App\Domain\ValueObjects\TagId;

class ArticleCategoryTagCollectionTest extends TestCase
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
    $tagCollection = new ArticleCategoryTagCollection($tagIds);

    // Then
    $this->assertInstanceOf(ArticleCategoryTagCollection::class, $tagCollection);
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $tagIds = [new TagId(), new TagId()];

    // When
    $tagCollection = new ArticleCategoryTagCollection($tagIds);

    // Then
    $this->assertEquals($tagIds, $tagCollection->toArray());
  }

  /** @test */
  public function testValidate_TooManyTags()
  {
    // Given
    $tagIds = array_fill(0, 11, new TagId());

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("タグの数が上限の10を超えています");
    new ArticleCategoryTagCollection($tagIds);
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
      new TagId(), // 6
      new TagId(), // 7
      new TagId(), // 8
      new TagId(), // 9
      new TagId(), // 10
    ];

    // When
    $tagCollection = new ArticleCategoryTagCollection($tagIds);

    // Then
    $this->assertInstanceOf(ArticleCategoryTagCollection::class, $tagCollection);
    $this->assertEquals(10, $tagCollection->count());
  }

  /** @test */
  public function testValidate_InvalidTagIdType()
  {
    // Given
    $tagIds = [new TagId(), 'invalid_tag_id'];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("無効なTagIdタイプです: string");
    new ArticleCategoryTagCollection($tagIds);
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
    new ArticleCategoryTagCollection($tagIds);
  }

  /** @test */
  public function testCount()
  {
    // Given
    $tagIds = [new TagId(), new TagId(), new TagId()];

    // When
    $tagCollection = new ArticleCategoryTagCollection($tagIds);

    // Then
    $this->assertEquals(3, $tagCollection->count());
  }

  /** @test */
  public function testEquals_SameCollection()
  {
    // Given
    $tagIds = [new TagId(), new TagId()];
    $collection1 = new ArticleCategoryTagCollection($tagIds);
    $collection2 = new ArticleCategoryTagCollection($tagIds);

    // When & Then
    $this->assertTrue($collection1->equals($collection2));
  }

  /** @test */
  public function testEquals_DifferentCollection()
  {
    // Given
    $collection1 = new ArticleCategoryTagCollection([new TagId(), new TagId()]);
    $collection2 = new ArticleCategoryTagCollection([new TagId(), new TagId()]);

    // When & Then
    $this->assertFalse($collection1->equals($collection2));
  }

  /** @test */
  public function testEquals_DifferentCount()
  {
    // Given
    $collection1 = new ArticleCategoryTagCollection([new TagId(), new TagId()]);
    $collection2 = new ArticleCategoryTagCollection([new TagId()]);

    // When & Then
    $this->assertFalse($collection1->equals($collection2));
  }

  /** @test */
  public function testMap()
  {
    // Given
    $tagIds = [$a = new TagId(), $b = new TagId(), $c = new TagId()];
    $tagCollection = new ArticleCategoryTagCollection($tagIds);

    // When
    $result = $tagCollection->map(function (TagId $tagId) {
      return $tagId->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertEquals([$a->toString(), $b->toString(), $c->toString()], $result);
  }

  /** @test */
  public function testMap_WithEmptyCollection()
  {
    // Given
    $tagCollection = new ArticleCategoryTagCollection([]);

    // When
    $result = $tagCollection->map(function (TagId $tagId) {
      return $tagId->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertEmpty($result);
  }

  /** @test */
  public function testMap_PreservesKeys()
  {
    // Given
    $tagIds = [
      'key1' => $a = new TagId(),
      'key2' => $b = new TagId(),
      'key3' => $c = new TagId()
    ];
    $tagCollection = new ArticleCategoryTagCollection($tagIds);

    // When
    $result = $tagCollection->map(function (TagId $tagId) {
      return $tagId->toString();
    });

    // Then
    $this->assertIsArray($result);
    $this->assertEquals([
      'key1' => $a->toString(),
      'key2' => $b->toString(),
      'key3' => $c->toString()
    ], $result);
  }
}
