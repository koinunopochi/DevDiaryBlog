<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;
use Tests\TestCase;
use App\Domain\Entities\Tag;

class TagTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $tagId = new TagId();
    $tagName = new TagName('テストタグ');

    // When
    $tag = new Tag($tagId, $tagName);

    // Then
    $this->assertInstanceOf(Tag::class, $tag);
    $this->assertEquals($tagId, $tag->getId());
    $this->assertEquals($tagName, $tag->getName());
  }
}
