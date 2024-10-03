<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\FindArticleByIdUseCase;
use App\Domain\Entities\Article;
use App\Domain\ValueObjects\ArticleId;
use App\Infrastructure\Persistence\EloquentArticleRepository;
use App\Models\EloquentArticle;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindArticleByIdUseCaseTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    config()->set('logging.default', 'stderr');
  }

  /**
   * @test
   */
  public function testCanFindArticleById(): void
  {
    // Given
    $tags = EloquentTag::factory(3)->create();
    $eloquentArticle = EloquentArticle::factory()->create();
    $eloquentArticle->tags()->attach($tags->pluck('id'));

    $findUseCase = new FindArticleByIdUseCase(new EloquentArticleRepository());

    // When
    $foundArticle = $findUseCase->execute(new ArticleId($eloquentArticle->id));

    // Then
    $this->assertInstanceOf(Article::class, $foundArticle);
    $this->assertEquals($eloquentArticle->id, $foundArticle->getId()->toString());
    $this->assertEquals($eloquentArticle->title, $foundArticle->getTitle()->toString());
    $this->assertEquals($eloquentArticle->content, $foundArticle->getContent()->toString());
    $this->assertEquals($eloquentArticle->author_id, $foundArticle->getAuthorId()->toString());
    $this->assertEquals($eloquentArticle->category_id, $foundArticle->getCategoryId()->toString());

    // タグの比較
    $foundTagIds = array_map(function ($tagId) {
      return $tagId->toString();
    }, $foundArticle->getTags()->toArray());

    $this->assertCount($tags->count(), $foundTagIds);
    foreach ($tags as $tag) {
      $this->assertTrue(in_array($tag->id, $foundTagIds));
    }

    $this->assertEquals($eloquentArticle->status, $foundArticle->getStatus()->toString());
  }

  /**
   * @test
   */
  public function testReturnsNullWhenArticleNotFound(): void
  {
    // Given
    $nonExistentArticleId = new ArticleId();
    $findUseCase = new FindArticleByIdUseCase(new EloquentArticleRepository());

    // When
    $result = $findUseCase->execute($nonExistentArticleId);

    // Then
    $this->assertNull($result);
  }
}
