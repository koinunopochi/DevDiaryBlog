<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\DraftArticle;
use App\Domain\Entities\Article;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleStatus;
use App\Domain\ValueObjects\DateTime;
use App\Domain\ValueObjects\ArticleTitle;
use App\Domain\ValueObjects\ArticleContent;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ArticleCategoryId;
use App\Domain\ValueObjects\ArticleTagCollection;
use App\Domain\ValueObjects\TagId;
use Tests\TestCase;

class DraftArticleTest extends TestCase
{
  /** @test */
  public function testCreateInstance()
  {
    // Given
    $articleId = new ArticleId();
    $status = new ArticleStatus(ArticleStatus::STATUS_DRAFT);
    $createdAt = new DateTime();

    // When
    $draftArticle = new DraftArticle($articleId, $status, $createdAt);

    // Then
    $this->assertInstanceOf(DraftArticle::class, $draftArticle);
    $this->assertEquals($articleId, $draftArticle->getId());
    $this->assertEquals($status, $draftArticle->getStatus());
    $this->assertEquals($createdAt, $draftArticle->getCreatedAt());
  }

  /** @test */
  public function testToArticle()
  {
    // Given
    $draftArticle = $this->createDraftArticle();
    $title = new ArticleTitle('新しい記事のタイトル');
    $content = new ArticleContent('新しい記事の内容');
    $authorId = new UserId();
    $categoryId = new ArticleCategoryId();
    $tags = new ArticleTagCollection([new TagId(), new TagId()]);
    $updatedAt = new DateTime();

    // When
    $article = $draftArticle->toArticle($title, $content, $authorId, $categoryId, $tags, $updatedAt);

    // Then
    $this->assertInstanceOf(Article::class, $article);
    $this->assertEquals($draftArticle->getId(), $article->getId());
    $this->assertEquals($title, $article->getTitle());
    $this->assertEquals($content, $article->getContent());
    $this->assertEquals($authorId, $article->getAuthorId());
    $this->assertEquals($categoryId, $article->getCategoryId());
    $this->assertEquals($tags, $article->getTags());
    $this->assertEquals($draftArticle->getStatus(), $article->getStatus());
    $this->assertEquals($draftArticle->getCreatedAt(), $article->getCreatedAt());
    $this->assertEquals($updatedAt, $article->getUpdatedAt());
  }

  private function createDraftArticle(): DraftArticle
  {
    return new DraftArticle(
      new ArticleId(),
      new ArticleStatus(ArticleStatus::STATUS_DRAFT),
      new DateTime()
    );
  }
}
