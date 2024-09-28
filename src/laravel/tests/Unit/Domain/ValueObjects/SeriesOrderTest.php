<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\SeriesOrder;
use Tests\TestCase;

class SeriesOrderTest extends TestCase
{
  /**
   * @test
   */
  public function testCreateInstance(): void
  {
    // Given
    $order = 1;

    // When
    $seriesOrderObject = new SeriesOrder($order);

    // Then
    $this->assertInstanceOf(SeriesOrder::class, $seriesOrderObject);
  }

  /**
   * @test
   */
  public function testToInt(): void
  {
    // Given
    $order = 10;

    // When
    $seriesOrderObject = new SeriesOrder($order);

    // Then
    $this->assertEquals($order, $seriesOrderObject->toInt());
  }

  /**
   * @test
   */
  public function testMinimumValidValue(): void
  {
    // Given
    $order = 1; // 最小の有効な値

    // When
    $seriesOrderObject = new SeriesOrder($order);

    // Then
    $this->assertInstanceOf(SeriesOrder::class, $seriesOrderObject);
    $this->assertEquals($order, $seriesOrderObject->toInt());
  }

  /**
   * @test
   */
  public function testMaximumValidValue(): void
  {
    // Given
    $order = 499; // 最大の有効な値

    // When
    $seriesOrderObject = new SeriesOrder($order);

    // Then
    $this->assertInstanceOf(SeriesOrder::class, $seriesOrderObject);
    $this->assertEquals($order, $seriesOrderObject->toInt());
  }

  /**
   * @test
   */
  public function testBelowMinimumValue(): void
  {
    // Given
    $order = 0; // 最小値未満

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('シリーズの順序の値は1以上である必要があります。');
    new SeriesOrder($order);
  }

  /**
   * @test
   */
  public function testAboveMaximumValue(): void
  {
    // Given
    $order = 500; // 最大値以上

    // When & Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('シリーズの順序の値は500未満である必要があります。');
    new SeriesOrder($order);
  }

  /**
   * @test
   */
  public function testEquals(): void
  {
    // Given
    $order1 = new SeriesOrder(5);
    $order2 = new SeriesOrder(5);
    $order3 = new SeriesOrder(10);

    // When & Then
    $this->assertTrue($order1->equals($order2));
    $this->assertFalse($order1->equals($order3));
  }

  /**
   * @test
   */
  public function testIsLessThan(): void
  {
    // Given
    $order1 = new SeriesOrder(5);
    $order2 = new SeriesOrder(10);

    // When & Then
    $this->assertTrue($order1->isLessThan($order2));
    $this->assertFalse($order2->isLessThan($order1));
  }

  /**
   * @test
   */
  public function testIsGreaterThan(): void
  {
    // Given
    $order1 = new SeriesOrder(15);
    $order2 = new SeriesOrder(10);

    // When & Then
    $this->assertTrue($order1->isGreaterThan($order2));
    $this->assertFalse($order2->isGreaterThan($order1));
  }
}
