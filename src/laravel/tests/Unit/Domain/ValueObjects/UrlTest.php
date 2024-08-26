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
}
