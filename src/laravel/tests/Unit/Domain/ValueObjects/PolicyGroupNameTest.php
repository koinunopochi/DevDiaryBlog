<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyGroupName;
use Tests\TestCase;

class PolicyGroupNameTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $policyGroupNameString = 'テストポリシー名';

    // When
    $policyGroupName = new PolicyGroupName($policyGroupNameString);

    // Then
    $this->assertInstanceOf(PolicyGroupName::class, $policyGroupName);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $policyGroupNameString = 'テストポリシー名';
    $policyGroupName = new PolicyGroupName($policyGroupNameString);

    // When
    $result = $policyGroupName->toString();

    // Then
    $this->assertEquals($policyGroupNameString, $result);
  }


  /** @test */
  public function testValidate()
  {
    // 0文字
    $this->expectException(\InvalidArgumentException::class);
    new PolicyGroupName('');

    // 1文字
    $policyGroupName = new PolicyGroupName('a');
    $this->assertInstanceOf(PolicyGroupName::class, $policyGroupName);

    // 50文字
    $policyGroupName = new PolicyGroupName(str_repeat('a', 50));
    $this->assertInstanceOf(PolicyGroupName::class, $policyGroupName);

    // 51文字
    $this->expectException(\InvalidArgumentException::class);
    new PolicyGroupName(str_repeat('a', 51));
  }
}
