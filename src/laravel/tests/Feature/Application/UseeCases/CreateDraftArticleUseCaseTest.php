<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\UseCases\CreateDraftArticleUseCase;
use App\Domain\Entities\DraftArticle;
use App\Domain\Repositories\ArticleRepositoryInterface;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\ArticleStatus;
use App\Infrastructure\Persistence\EloquentArticleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateDraftArticleUseCaseTest extends TestCase
{
  use RefreshDatabase;

  private ArticleRepositoryInterface $articleRepository;
  private CreateDraftArticleUseCase $useCase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->articleRepository = new EloquentArticleRepository();
    $this->useCase = new CreateDraftArticleUseCase($this->articleRepository);
  }

  /**
   * @test
   */
  public function testCanCreateDraftArticle(): void
  {
    // When
    $draftArticle = $this->useCase->execute();

    // Then
    $this->assertInstanceOf(DraftArticle::class, $draftArticle);
    $this->assertInstanceOf(ArticleId::class, $draftArticle->getId());
    $this->assertInstanceOf(ArticleStatus::class, $draftArticle->getStatus());
    $this->assertEquals(ArticleStatus::STATUS_DRAFT, $draftArticle->getStatus()->toString());
  }
}
