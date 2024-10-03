<?php

namespace App\Application\DataTransferObjects;

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
use Illuminate\Http\Request;

class SaveArticleDTO
{
  private ArticleId $id;
  private ArticleTitle $title;
  private ArticleContent $content;
  private UserId $authorId;
  private ArticleCategoryId $categoryId;
  private ArticleTagCollection $tags;
  private ArticleStatus $status;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(Request $request)
  {
    $articleId = $request->input(key: 'articleId');
    $this->id = $articleId ? new ArticleId($articleId) : new ArticleId();
    $this->title = new ArticleTitle($request->input('title'));
    $this->content = new ArticleContent($request->input('content'));
    $this->authorId = new UserId($request->input('authorId'));
    $this->categoryId = new ArticleCategoryId($request->input('categoryId'));

    // タグの変換処理
    $tagIds = $request->input('tags', []);
    $tagObjects = array_map(function ($tagId) {
      return new TagId($tagId);
    }, $tagIds);
    $this->tags = new ArticleTagCollection($tagObjects);

    $this->status = new ArticleStatus($request->input('status'));
    $this->createdAt = new DateTime($request->input('createdAt', 'now'));
    $this->updatedAt = new DateTime($request->input('updatedAt', 'now'));
  }

  public function toArticle(): Article
  {
    return new Article(
      $this->id,
      $this->title,
      $this->content,
      $this->authorId,
      $this->categoryId,
      $this->tags,
      $this->status,
      $this->createdAt,
      $this->updatedAt
    );
  }
}
