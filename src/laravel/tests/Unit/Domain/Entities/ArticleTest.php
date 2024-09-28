<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Article;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleTagCollection;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\TagId;
use Tests\TestCase;

class ArticleTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $articleId = new ArticleId();
    $title = new ArticleTitle('テスト記事');
    $content = new ArticleContent('これはテスト記事の内容です。');
    $authorId = new UserId();
    $categoryId = new ArticleCategoryId();
    $tags = new ArticleTagCollection([new TagId(), new TagId()]);
    $status = new ArticleStatus(ArticleStatus::STATUS_DRAFT);
    $createdAt = new DateTime();
    $updatedAt = new DateTime();

    // When
    $article = new Article($articleId, $title, $content, $authorId, $categoryId, $tags, $status, $createdAt, $updatedAt);

    // Then
    $this->assertInstanceOf(Article::class, $article);
    $this->assertEquals($articleId, $article->getId());
    $this->assertEquals($title, $article->getTitle());
    $this->assertEquals($content, $article->getContent());
    $this->assertEquals($authorId, $article->getAuthorId());
    $this->assertEquals($categoryId, $article->getCategoryId());
    $this->assertEquals($tags, $article->getTags());
    $this->assertEquals($status, $article->getStatus());
    $this->assertEquals($createdAt, $article->getCreatedAt());
    $this->assertEquals($updatedAt, $article->getUpdatedAt());
  }

  /** @test */
  public function testUpdateTitle()
  {
    // Given
    $originalArticle = $this->createArticle();
    $newTitle = new ArticleTitle('新しいタイトル');
    $newUpdatedAt = new DateTime();

    // When
    $updatedArticle = $originalArticle->updateTitle($newTitle, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalArticle, $updatedArticle);
    $this->assertEquals($newTitle, $updatedArticle->getTitle());
    $this->assertEquals($newUpdatedAt, $updatedArticle->getUpdatedAt());
    $this->assertNotEquals($newTitle, $originalArticle->getTitle());
    $this->assertNotEquals($newUpdatedAt, $originalArticle->getUpdatedAt());
    $this->assertOtherPropertiesUnchanged($originalArticle, $updatedArticle, ['title', 'updatedAt']);
  }

  /** @test */
  public function testUpdateContent()
  {
    // Given
    $originalArticle = $this->createArticle();
    $newContent = new ArticleContent('新しい内容');
    $newUpdatedAt = new DateTime();

    // When
    $updatedArticle = $originalArticle->updateContent($newContent, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalArticle, $updatedArticle);
    $this->assertEquals($newContent, $updatedArticle->getContent());
    $this->assertEquals($newUpdatedAt, $updatedArticle->getUpdatedAt());
    $this->assertNotEquals($newContent, $originalArticle->getContent());
    $this->assertNotEquals($newUpdatedAt, $originalArticle->getUpdatedAt());
    $this->assertOtherPropertiesUnchanged($originalArticle, $updatedArticle, ['content', 'updatedAt']);
  }

  /** @test */
  public function testUpdateAll()
  {
    // Given
    $originalArticle = $this->createArticle();
    $newTitle = new ArticleTitle('新しいタイトル');
    $newContent = new ArticleContent('新しい内容');
    $newCategoryId = new ArticleCategoryId();
    $newTags = new ArticleTagCollection([new TagId(), new TagId(), new TagId()]);
    $newStatus = new ArticleStatus(ArticleStatus::STATUS_PUBLISHED);
    $newUpdatedAt = new DateTime();

    // When
    $updatedArticle = $originalArticle->updateAll($newTitle, $newContent, $newCategoryId, $newTags, $newStatus, $newUpdatedAt);

    // Then
    $this->assertNotSame($originalArticle, $updatedArticle);
    $this->assertEquals($newTitle, $updatedArticle->getTitle());
    $this->assertEquals($newContent, $updatedArticle->getContent());
    $this->assertEquals($newCategoryId, $updatedArticle->getCategoryId());
    $this->assertEquals($newTags, $updatedArticle->getTags());
    $this->assertEquals($newStatus, $updatedArticle->getStatus());
    $this->assertEquals($newUpdatedAt, $updatedArticle->getUpdatedAt());
    $this->assertNotEquals($newTitle, $originalArticle->getTitle());
    $this->assertNotEquals($newContent, $originalArticle->getContent());
    $this->assertNotEquals($newCategoryId, $originalArticle->getCategoryId());
    $this->assertNotEquals($newTags, $originalArticle->getTags());
    $this->assertNotEquals($newStatus, $originalArticle->getStatus());
    $this->assertNotEquals($newUpdatedAt, $originalArticle->getUpdatedAt());
    $this->assertEquals($originalArticle->getId(), $updatedArticle->getId());
    $this->assertEquals($originalArticle->getAuthorId(), $updatedArticle->getAuthorId());
    $this->assertEquals($originalArticle->getCreatedAt(), $updatedArticle->getCreatedAt());
  }

  private function createArticle(): Article
  {
    return new Article(
      new ArticleId(),
      new ArticleTitle('元のタイトル'),
      new ArticleContent('元の内容'),
      new UserId(),
      new ArticleCategoryId(),
      new ArticleTagCollection([new TagId()]),
      new ArticleStatus(ArticleStatus::STATUS_DRAFT),
      new DateTime(),
      new DateTime()
    );
  }

  private function assertOtherPropertiesUnchanged(Article $original, Article $updated, array $changedProperties)
  {
    $properties = ['id', 'title', 'content', 'authorId', 'categoryId', 'tags', 'status', 'createdAt', 'updatedAt'];
    foreach ($properties as $property) {
      if (!in_array($property, $changedProperties)) {
        $getter = 'get' . ucfirst($property);
        $this->assertEquals($original->$getter(), $updated->$getter());
      }
    }
  }
}
