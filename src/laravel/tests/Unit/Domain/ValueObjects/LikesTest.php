<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\Likes;
use InvalidArgumentException;

class LikesTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // config()->set('logging.default', 'stderr');
  }

  /** @test */
  public function testCreateInstance()
  {
    // Given
    $value = 10;

    // When
    $likes = new Likes($value);

    // Then
    $this->assertInstanceOf(Likes::class, $likes);
    $this->assertEquals($value, $likes->getValue());
  }

  /** @test */
  public function testValidate_NegativeValue()
  {
    // Given
    $value = -1;

    // When & Then
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('いいね数は0以上である必要があります');
    new Likes($value);
  }

  /** @test */
  public function testValidate_ZeroValue()
  {
    // Given
    $value = 0;

    // When
    $likes = new Likes($value);

    // Then
    $this->assertEquals($value, $likes->getValue());
  }

  /** @test */
  public function testIncrement()
  {
    // Given
    $likes = new Likes(5);

    // When
    $newLikes = $likes->increment();

    // Then
    $this->assertInstanceOf(Likes::class, $newLikes);
    $this->assertEquals(6, $newLikes->getValue());
    $this->assertEquals(5, $likes->getValue()); // 元のオブジェクトは変更されていないことを確認
  }

  /** @test */
  public function testDecrement()
  {
    // Given
    $likes = new Likes(5);

    // When
    $newLikes = $likes->decrement();

    // Then
    $this->assertInstanceOf(Likes::class, $newLikes);
    $this->assertEquals(4, $newLikes->getValue());
    $this->assertEquals(5, $likes->getValue()); // 元のオブジェクトは変更されていないことを確認
  }

  /** @test */
  public function testDecrement_ZeroValue()
  {
    // Given
    $likes = new Likes(0);

    // When
    $newLikes = $likes->decrement();

    // Then
    $this->assertEquals(0, $newLikes->getValue()); // 0未満にならないことを確認
  }

  /** @test */
  public function testEquals_SameValue()
  {
    // Given
    $likes1 = new Likes(10);
    $likes2 = new Likes(10);

    // When & Then
    $this->assertTrue($likes1->equals($likes2));
  }

  /** @test */
  public function testEquals_DifferentValue()
  {
    // Given
    $likes1 = new Likes(10);
    $likes2 = new Likes(20);

    // When & Then
    $this->assertFalse($likes1->equals($likes2));
  }

  /** @test */
  public function testToString()
  {
    // Given
    $value = 42;
    $likes = new Likes($value);

    // When
    $result = $likes->toString();

    // Then
    $this->assertEquals((string)$value, $result);
    $this->assertIsString($result);
  }

  /** @test */
  public function testToString_ZeroValue()
  {
    // Given
    $likes = new Likes(0);

    // When
    $result = $likes->toString();

    // Then
    $this->assertEquals('0', $result);
  }
}
