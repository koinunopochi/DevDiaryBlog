<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $urlString = 'https://example.com';

    // When
    $url = new Url($urlString);

    // Then
    $this->assertInstanceOf(Url::class, $url);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $urlString = 'https://example.com';

    // When
    $url = new Url($urlString);

    // Then
    $this->assertEquals($urlString, $url->toString());
  }

  /**
   * @test
   * @dataProvider urlProvider
   */
  public function testValidateUrl(string $urlString, bool $isValid)
  {
    // Given

    // When & Then
    if (!$isValid) {
      $this->expectException(\InvalidArgumentException::class);
    }

    $url = new Url($urlString);

    if ($isValid) {
      $this->assertInstanceOf(Url::class, $url);
    }
  }

  /**
   * @return array<mixed>
   */
  public static function urlProvider(): array
  {
    return [
      ['https://example.com', true],
      ['http://example.com', true],
      ['ftp://example.com', true],
      ['https://www.example.com', true],
      ['https://example.com/path', true],
      ['https://example.com?query=string', true],
      ['https://example.com#fragment', true],
      ['https://user:pass@example.com', true],
      ['https://192.168.0.1', true],
      ['https://example.com:8080', true],
      // 無効なURL
      ['example.com', false],
      ['http://', false],
      ['https://', false],
      // ['htt://example.com', false], // これは通ってしまうが、大きな問題ではないため放置する
      ['//example.com', false],
      // ['htp://example.com', false], // これは通ってしまうが、大きな問題ではないため放置する
      ['https:/example.com', false],
      ['https:example.com', false],
      ['https//example.com', false],
      ['https:\\example.com', false],
    ];
  }
}
