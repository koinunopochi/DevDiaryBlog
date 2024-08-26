<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\SocialLinkCollection;

class SocialLinkCollectionTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $socialLinks = [
      'twitter' => 'https://twitter.com/example',
    ];

    // When
    $socialLinkCollection = new SocialLinkCollection($socialLinks);

    // Then
    $this->assertInstanceOf(SocialLinkCollection::class, $socialLinkCollection);
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $socialLinks = [
      'twitter' => 'https://twitter.com/example',
    ];

    // When
    $socialLinkCollection = new SocialLinkCollection($socialLinks);

    // Then
    $this->assertEquals($socialLinks, $socialLinkCollection->toArray());
  }
}
