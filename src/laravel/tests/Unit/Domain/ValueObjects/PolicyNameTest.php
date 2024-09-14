<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyName;
use Tests\TestCase;

class PolicyNameTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    $policyNameString = 'テストポリシー名';
    $policyName = new PolicyName($policyNameString);

    $this->assertInstanceOf(PolicyName::class, $policyName);
  }

  /** @test */
  public function testToString()
  {
    $policyNameString = 'テストポリシー名';
    $policyName = new PolicyName($policyNameString);

    $result = $policyName->toString();

    $this->assertEquals($policyNameString, $result);
  }

  /**
   * @test
   * @dataProvider provideValidPolicyNames
   */
  public function testValidPolicyNames($policyNameString)
  {
    $policyName = new PolicyName($policyNameString);
    $this->assertInstanceOf(PolicyName::class, $policyName);
  }

  public static function provideValidPolicyNames()
  {
    return [
      ['a'],                                                   // 1文字
      [str_repeat('a', 50)],                    // 50文字 (半角)
      ['あ'],                                                  // 1文字 (全角)
      ['ユーザー自己情報読み取り（パターン一致）'],              // 本番のような文字
      [str_repeat('あ', 50)],                   // 100バイト (全角)   50文字となる
      [str_repeat('（', 50)],                   // 50文字 (記号)
    ];
  }

  /**
   * @test
   * @dataProvider provideInvalidPolicyNames
   */
  public function testInvalidPolicyNames($policyNameString)
  {
    $this->expectException(\InvalidArgumentException::class);
    new PolicyName($policyNameString);
  }

  public static function provideInvalidPolicyNames()
  {
    return [
      [''],                                     // 0文字
      [str_repeat('a', 51)],                    // 51文字 (半角)
      [str_repeat('あ', 51)],                    // 102バイト (全角)
    ];
  }
}
