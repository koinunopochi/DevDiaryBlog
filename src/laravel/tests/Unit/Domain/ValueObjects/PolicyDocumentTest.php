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
    $policyDocumentString = '{"statement": [{"effect": "allow"}]}';

    // When
    $policyDocument = new PolicyDocument($policyDocumentString);

    // Then
    $this->assertInstanceOf(PolicyDocument::class, $policyDocument);
  }

  /** @test */
  public function testToString()
  {
    // Given
    $policyDocumentString = '{"statement": [{"effect": "allow"}]}';
    $policyDocument = new PolicyDocument($policyDocumentString);

    // When
    $result = $policyDocument->toString();

    // Then
    $this->assertEquals($policyDocumentString, $result);
  }
}
