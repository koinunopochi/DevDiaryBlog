<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\ArticleCard;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleAuthor;
use App\Domain\ValueObjects\Likes;
use App\Domain\ValueObjects\ArticleTagNameCollection;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\TagName;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\Username;
use Tests\TestCase;

class ArticleCardTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $articleId = new ArticleId();
    $title = new ArticleTitle('テスト記事');
    $author = new ArticleAuthor(
      new Username('JohnDoe'),
      new DisplayName('johndoe'),
      new Url('http://example.com/johndoe.jpg')
    );
    $likes = new Likes(42);
    $tags = new ArticleTagNameCollection([new TagName('PHP'), new TagName('Laravel')]);
    $createdAt = new DateTime();
    $updatedAt = new DateTime();

    // When
    $articleCard = new ArticleCard($articleId, $title, $author, $likes, $tags, $createdAt, $updatedAt);

    // Then
    $this->assertInstanceOf(ArticleCard::class, $articleCard);
    $this->assertEquals($articleId, $articleCard->getId());
    $this->assertEquals($title, $articleCard->getTitle());
    $this->assertEquals($author, $articleCard->getAuthor());
    $this->assertEquals($likes, $articleCard->getLikes());
    $this->assertEquals($tags, $articleCard->getTags());
    $this->assertEquals($createdAt, $articleCard->getCreatedAt());
    $this->assertEquals($updatedAt, $articleCard->getUpdatedAt());
  }

  /** @test */
  public function testToArray()
  {
    // Given
    $articleCard = $this->createArticleCard();

    // When
    $result = $articleCard->toArray();

    // Then
    $this->assertIsArray($result);
    $this->assertArrayHasKey('id', $result);
    $this->assertArrayHasKey('title', $result);
    $this->assertArrayHasKey('author', $result);
    $this->assertArrayHasKey('likes', $result);
    $this->assertArrayHasKey('tags', $result);
    $this->assertArrayHasKey('createdAt', $result);
    $this->assertArrayHasKey('updatedAt', $result);

    $this->assertEquals($articleCard->getId()->toString(), $result['id']);
    $this->assertEquals($articleCard->getTitle()->toString(), $result['title']);
    $this->assertEquals($articleCard->getAuthor()->toArray(), $result['author']);
    $this->assertEquals($articleCard->getLikes()->getValue(), $result['likes']);
    $this->assertEquals($articleCard->getTags()->toArray(), $result['tags']);
    $this->assertEquals($articleCard->getCreatedAt()->toString(), $result['createdAt']);
    $this->assertEquals($articleCard->getUpdatedAt()->toString(), $result['updatedAt']);
  }

  /** @test */
  public function testImmutability()
  {
    // Given
    $originalArticleCard = $this->createArticleCard();

    // When
    $newTitle = new ArticleTitle('新しいタイトル');
    $newLikes = new Likes(100);

    // Then
    $this->assertNotEquals($newTitle, $originalArticleCard->getTitle());
    $this->assertNotEquals($newLikes, $originalArticleCard->getLikes());
  }

  private function createArticleCard(): ArticleCard
  {
    return new ArticleCard(
      new ArticleId(),
      new ArticleTitle('テスト記事'),
      new ArticleAuthor(
        new Username('JohnDoe'),
        new DisplayName('johndoe'),
        new Url('http://example.com/johndoe.jpg')
      ),
      new Likes(42),
      new ArticleTagNameCollection([new TagName('PHP'), new TagName('Laravel')]),
      new DateTime(),
      new DateTime()
    );
  }
}
