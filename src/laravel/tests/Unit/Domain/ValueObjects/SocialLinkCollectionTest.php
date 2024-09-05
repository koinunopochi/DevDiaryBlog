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

  /** @test */
  public function testValidate_SocialLinksCountTooMany()
  {
    // Given
    $socialLinks = [
      'twitter_1' => 'https://twitter.com/example',
      'facebook_2' => 'https://facebook.com/example',
      'instagram_3' => 'https://instagram.com/example',
      'linkedin_4' => 'https://linkedin.com/example',
      'github_5' => 'https://github.com/example',
      'youtube_6' => 'https://youtube.com/example',
      'tiktok_7' => 'https://tiktok.com/example',
      'x_8' => 'https://x.com/example',
      'other_9' => 'https://example.com/other',
      'other_10' => 'https://example.com/other',
      'other_11' => 'https://example.com/other',
      'other_12' => 'https://example.com/other',
      'other_13' => 'https://example.com/other',
      'other_14' => 'https://example.com/other',
      'other_15' => 'https://example.com/other',
      'other_16' => 'https://example.com/other',
    ];

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    new SocialLinkCollection($socialLinks);
  }

  /** @test */
  public function testValidate_SocialLinksCountOk()
  {
    // Given
    $socialLinks = [
      'twitter_1' => 'https://twitter.com/example',
      'facebook_2' => 'https://facebook.com/example',
      'instagram_3' => 'https://instagram.com/example',
      'linkedin_4' => 'https://linkedin.com/example',
      'github_5' => 'https://github.com/example',
      'youtube_6' => 'https://youtube.com/example',
      'tiktok_7' => 'https://tiktok.com/example',
      'x_8' => 'https://x.com/example',
      'other_9' => 'https://example.com/other',
      'other_10' => 'https://example.com/other',
      'other_11' => 'https://example.com/other',
      'other_12' => 'https://example.com/other',
      'other_13' => 'https://example.com/other',
      'other_14' => 'https://example.com/other',
      'other_15' => 'https://example.com/other',
    ];

    // When
    $socialLinkCollection = new SocialLinkCollection($socialLinks);

    // Then
    $this->assertInstanceOf(SocialLinkCollection::class, $socialLinkCollection);
  }
}
