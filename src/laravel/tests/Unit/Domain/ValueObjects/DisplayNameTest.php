<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\DisplayName;
use Tests\TestCase;

class DisplayNameTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $displayNameString = 'テストユーザー';

    // When
    $displayName = new DisplayName($displayNameString);

    // Then
    $this->assertInstanceOf(DisplayName::class, $displayName);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $displayNameString = 'テストユーザー';
    $displayName = new DisplayName($displayNameString);

    // When
    $result = $displayName->toString();

    // Then
    $this->assertEquals($displayNameString, $result);
  }

  /** @test */
  public function testValidateEnglish()
  {
    // 0文字
    $this->expectException(\InvalidArgumentException::class);
    new DisplayName('');

    // 1文字
    $displayName = new DisplayName('a');
    $this->assertInstanceOf(DisplayName::class, $displayName);

    // 49文字
    $displayName = new DisplayName(str_repeat('a', 49));
    $this->assertInstanceOf(DisplayName::class, $displayName);

    // 50文字
    $displayName = new DisplayName(str_repeat('a', 50));
    $this->assertInstanceOf(DisplayName::class, $displayName);

    // 51文字
    $this->expectException(\InvalidArgumentException::class);
    new DisplayName(str_repeat('a', 51));
  }

  /** @test */
  public function testValidateJapanese()
  {
    // 0文字
    $this->expectException(\InvalidArgumentException::class);
    new DisplayName('');

    // 1文字
    $displayName = new DisplayName('あ');
    $this->assertInstanceOf(DisplayName::class, $displayName);

    // 49文字
    $displayName = new DisplayName(str_repeat('あ', 49));
    $this->assertInstanceOf(DisplayName::class, $displayName);

    // 50文字
    $displayName = new DisplayName(str_repeat('あ', 50));
    $this->assertInstanceOf(DisplayName::class, $displayName);

    // 51文字
    $this->expectException(\InvalidArgumentException::class);
    new DisplayName(str_repeat('あ', 51));
  }
}
