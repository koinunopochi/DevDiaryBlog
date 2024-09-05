<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\SocialLinkCollection;

class SocialLinkCollectionTest extends TestCase
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

  /** @test */
  public function testValidate_KeyLengthTooLong()
  {
    // Given
    $socialLinks = [
      str_repeat('a', 51) => 'https://example.com',
    ];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SocialLinkCollection($socialLinks);
  }

  /** @test */
  public function testValidate_KeyIsEmpty()
  {
    // Given
    $socialLinks = [
      '' => 'https://example.com',
    ];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SocialLinkCollection($socialLinks);
  }

  /** @test */
  public function testValidate_KeyLengthOk()
  {
    // Given
    $socialLinks = [
      str_repeat('a', 50) => 'https://example.com',
    ];

    // When
    $socialLinkCollection = new SocialLinkCollection($socialLinks);

    // Then
    $this->assertInstanceOf(SocialLinkCollection::class, $socialLinkCollection);
  }

  /** @test */
  public function testValidate_InvalidUrl()
  {
    // Given
    $socialLinks = [
      'twitter' => 'invalid-url',
    ];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SocialLinkCollection($socialLinks);
  }

  /** @test */
  public function testValidate_UrlLengthTooLong()
  {
    // Given
    $baseUrl = "https://example.com/";
    $usrLength = strlen($baseUrl);
    $url = $baseUrl . str_repeat('a', 151 - $usrLength);

    $socialLinks = [
      'twitter' => $url,
    ];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SocialLinkCollection($socialLinks);
  }

  /** @test */
  public function testValidate_UrlLengthOk()
  {
    // Given
    $url = "https://example.com/";
    $usrLength = strlen($url);
    $socialLinks = [
      'twitter' => $url . str_repeat('a', 150 - $usrLength),
    ];

    // When
    $socialLinkCollection = new SocialLinkCollection($socialLinks);

    // Then
    $this->assertInstanceOf(SocialLinkCollection::class, $socialLinkCollection);
  }
}
