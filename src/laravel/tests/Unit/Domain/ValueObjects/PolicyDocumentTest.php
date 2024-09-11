<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PolicyDocument;
use Tests\TestCase;

class PolicyDocumentTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $policyDocumentArray = ['statement' => [['effect' => 'allow']]];

    // When
    $policyDocument = new PolicyDocument($policyDocumentArray);

    // Then
    $this->assertInstanceOf(PolicyDocument::class, $policyDocument);
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $policyDocumentArray = ['statement' => [['effect' => 'allow']]];
    $policyDocument = new PolicyDocument($policyDocumentArray);

    // When
    $result = $policyDocument->toArray();

    // Then
    $this->assertEquals($policyDocumentArray, $result);
  }
}
