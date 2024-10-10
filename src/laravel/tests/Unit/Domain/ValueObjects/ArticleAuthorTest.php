<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\ArticleAuthor;
use App\Domain\ValueObjects\Username;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\Url;

class ArticleAuthorTest extends TestCase
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
    $username = new Username('test_user');
    $displayName = new DisplayName('Test User');
    $profileImage = new Url('http://example.com/profile.jpg');

    // When
    $author = new ArticleAuthor($username, $displayName, $profileImage);

    // Then
    $this->assertInstanceOf(ArticleAuthor::class, $author);
  }

  /** @test */
  public function testGetUsername()
  {
    // Given
    $username = new Username('test_user');
    $displayName = new DisplayName('Test User');
    $profileImage = new Url('http://example.com/profile.jpg');
    $author = new ArticleAuthor($username, $displayName, $profileImage);

    // When
    $result = $author->getUsername();

    // Then
    $this->assertInstanceOf(Username::class, $result);
    $this->assertEquals('test_user', $result->toString());
  }

  /** @test */
  public function testGetDisplayName()
  {
    // Given
    $username = new Username('test_user');
    $displayName = new DisplayName('Test User');
    $profileImage = new Url('http://example.com/profile.jpg');
    $author = new ArticleAuthor($username, $displayName, $profileImage);

    // When
    $result = $author->getDisplayName();

    // Then
    $this->assertInstanceOf(DisplayName::class, $result);
    $this->assertEquals('Test User', $result->toString());
  }

  /** @test */
  public function testGetProfileImage()
  {
    // Given
    $username = new Username('test_user');
    $displayName = new DisplayName('Test User');
    $profileImage = new Url('http://example.com/profile.jpg');
    $author = new ArticleAuthor($username, $displayName, $profileImage);

    // When
    $result = $author->getProfileImage();

    // Then
    $this->assertInstanceOf(Url::class, $result);
    $this->assertEquals('http://example.com/profile.jpg', $result->toString());
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $username = new Username('test_user');
    $displayName = new DisplayName('Test User');
    $profileImage = new Url('http://example.com/profile.jpg');
    $author = new ArticleAuthor($username, $displayName, $profileImage);

    // When
    $result = $author->toArray();

    // Then
    $this->assertIsArray($result);
    $this->assertEquals([
      'username' => 'test_user',
      'displayName' => 'Test User',
      'profileImage' => 'http://example.com/profile.jpg'
    ], $result);
  }
}
