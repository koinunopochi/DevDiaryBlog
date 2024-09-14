<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyName;
use Tests\TestCase;

class PolicyNameTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $policyNameString = 'テストポリシー名';

    // When
    $policyName = new PolicyName($policyNameString);

    // Then
    $this->assertInstanceOf(PolicyName::class, $policyName);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $policyNameString = 'テストポリシー名';
    $policyName = new PolicyName($policyNameString);

    // When
    $result = $policyName->toString();

    // Then
    $this->assertEquals($policyNameString, $result);
  }


  /** @test */
  public function testValidate()
  {
    // 0文字
    $this->expectException(\InvalidArgumentException::class);
    new PolicyName('');

    // 1文字
    $policyName = new PolicyName('a');
    $this->assertInstanceOf(PolicyName::class, $policyName);

    // 50文字
    $policyName = new PolicyName(str_repeat('a', 50));
    $this->assertInstanceOf(PolicyName::class, $policyName);

    // 51文字
    $this->expectException(\InvalidArgumentException::class);
    new PolicyName(str_repeat('a', 51));
  }

  /** @test */
  public function test2byteValidate()
  {
    // 0文字
    $this->expectException(\InvalidArgumentException::class);
    new PolicyName('');

    // 1文字
    $policyName = new PolicyName('あ');
    $this->assertInstanceOf(PolicyName::class, $policyName);

    // 本番のような文字
    $policyName = new PolicyName('ユーザー自己情報読み取り（パターン一致）');
    $this->assertInstanceOf(PolicyName::class, $policyName);

    // 50文字
    $policyName = new PolicyName(str_repeat('あ', 50));
    $this->assertInstanceOf(PolicyName::class, $policyName);

    // 50文字（記号）
    $policyName = new PolicyName(str_repeat('（', 50));
    $this->assertInstanceOf(PolicyName::class, $policyName);

    // 51文字
    $this->expectException(\InvalidArgumentException::class);
    new PolicyName(str_repeat('あ', 51));
  }
}
