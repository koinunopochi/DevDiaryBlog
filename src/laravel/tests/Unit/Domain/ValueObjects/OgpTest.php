<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Ogp;
use App\Domain\ValueObjects\Url;
use Tests\TestCase;

class OgpTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $ogp = [
      'url' => 'https://example.com',
      'imageUrl' => 'https://example.com/test.png',
      'title' => 'test title',
    ];

    // When
    $ogpObject = new Ogp(
      url: new Url($ogp['url']),
      imageUrl: new Url($ogp['imageUrl']),
      title: $ogp['title'],
    );

    // Then
    $this->assertInstanceOf(Ogp::class, $ogpObject);
    $this->assertSame($ogp['url'], $ogpObject->getUrl()->toString());
    $this->assertEquals($ogp['imageUrl'], $ogpObject->getImageUrl()->toString());
    $this->assertEquals($ogp['title'], $ogpObject->getTitle());
  }
  /** @test */
  public function testCreateInstanceOnlyUrl()
  {
    // Given
    $ogp = [
      'url' => 'https://example.com',
    ];

    // When
    $ogpObject = new Ogp(
      url: new Url($ogp['url']),
      imageUrl: null,
      title: null,
    );

    // Then
    $this->assertInstanceOf(Ogp::class, $ogpObject);
    $this->assertSame($ogp['url'], $ogpObject->getUrl()->toString());
    $this->assertNull($ogpObject->getImageUrl());
    $this->assertNull($ogpObject->getTitle());
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $ogp = [
      'url' => 'https://example.com',
      'imageUrl' => 'https://example.com/test.png',
      'title' => 'test title',
    ];
    $ogpObject = new Ogp(
      url: new Url($ogp['url']),
      imageUrl: new Url($ogp['imageUrl']),
      title: $ogp['title'],
    );

    // When
    $result = $ogpObject->toArray();

    // Then
    $this->assertSame($ogp['url'], $result['url']);
    $this->assertEquals($ogp['imageUrl'], $result['imageUrl']);
    $this->assertEquals($ogp['title'], $result['title']);
  }
  /** @test */
  public function testToArrayOnlyUrl()
  {
    // Given
    $ogp = [
      'url' => 'https://example.com',
    ];
    $ogpObject = new Ogp(
      url: new Url($ogp['url']),
      imageUrl: null,
      title: null,
    );

    // When
    $result = $ogpObject->toArray();

    // Then
    $this->assertSame($ogp['url'], $result['url']);
    $this->assertNull($result['imageUrl']);
    $this->assertNull($result['title']);
  }
}
