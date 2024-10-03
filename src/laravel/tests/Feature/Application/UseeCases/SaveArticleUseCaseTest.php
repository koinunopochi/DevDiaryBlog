<?php

namespace Tests\Feature\Application\UseCases;

use App\Application\DataTransferObjects\SaveArticleDTO;
use App\Application\UseCases\SaveArticleUseCase;
use App\Domain\Entities\Article;
use App\Domain\ValueObjects\ArticleId;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\EloquentArticleRepository;
use App\Models\User as EloquentUser;
use App\Models\EloquentArticleCategory;
use App\Models\EloquentTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SaveArticleUseCaseTest extends TestCase
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
  public function testCanSaveArticle(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    $category = EloquentArticleCategory::factory()->create();
    $categoryId = $category->id;
    $eloquentTags = EloquentTag::factory(3)->create();
    $tags = $eloquentTags->pluck('id')->toArray();

    // Request
    $request = new Request([
      'title' => 'テスト記事',
      'content' => 'これはテスト記事の内容です。',
      'authorId' => $user->id,
      'categoryId' => $categoryId,
      'tags' => $tags,
      'status' => 'Draft',
    ]);

    $dto = new SaveArticleDTO($request);
    $useCase = new SaveArticleUseCase(new EloquentArticleRepository());

    // When
    $useCase->execute($dto);

    // Then
    $repository = new EloquentArticleRepository();
    $savedArticle = $repository->findByAuthorId(new UserId($user->id))->first();

    $this->assertInstanceOf(Article::class, $savedArticle);
    $this->assertEquals('テスト記事', $savedArticle->getTitle()->toString());
    $this->assertEquals('これはテスト記事の内容です。', $savedArticle->getContent()->toString());
    $this->assertEquals($user->id, $savedArticle->getAuthorId()->toString());
    $this->assertEquals($categoryId, $savedArticle->getCategoryId()->toString());

    // タグの比較
    $savedTagIds = array_map(function ($tagId) {
        return $tagId->toString();
      }, $savedArticle->getTags()->toArray());

    $this->assertCount(count($tags), $savedTagIds);
    foreach ($tags as $tag) {
      $this->assertTrue(in_array($tag, $savedTagIds));
    }

    $this->assertEquals('Draft', $savedArticle->getStatus()->toString());
  }

  /**
   * @test
   */
  /**
   * @test
   */
  public function testCanUpdateExistingArticle(): void
  {
    // Given
    $user = EloquentUser::factory()->create();
    $category = EloquentArticleCategory::factory()->create();
    $categoryId = $category->id;
    $existingArticleId = (new ArticleId())->toString();

    // 初期タグを作成
    $initialTags = EloquentTag::factory(2)->create();
    $initialTagIds = $initialTags->pluck('id')->toArray();

    // 既存の記事を保存
    $initialRequest = new Request([
      'title' => '初期タイトル',
      'content' => '初期内容',
      'authorId' => $user->id,
      'categoryId' => $categoryId,
      'tags' => $initialTagIds,
      'status' => 'Draft',
    ]);
    $initialDto = new SaveArticleDTO($initialRequest);
    $useCase = new SaveArticleUseCase(new EloquentArticleRepository());
    $useCase->execute($initialDto);

    // 更新用の新しいタグを作成
    $newTags = EloquentTag::factory(2)->create();
    $newTagIds = $newTags->pluck('id')->toArray();

    // 更新リクエスト
    $updateRequest = new Request([
      'title' => '更新されたタイトル',
      'content' => '更新された内容',
      'authorId' => $user->id,
      'categoryId' => $categoryId,
      'tags' => $newTagIds,
      'status' => 'Published',
    ]);
    $updateDto = new SaveArticleDTO($updateRequest);

    // When
    $useCase->execute($updateDto);

    // Then
    $repository = new EloquentArticleRepository();
    $updatedArticle = $repository->findById(new ArticleId($existingArticleId));

    $this->assertInstanceOf(Article::class, $updatedArticle);
    $this->assertEquals('更新されたタイトル', $updatedArticle->getTitle()->toString());
    $this->assertEquals('更新された内容', $updatedArticle->getContent()->toString());
    $this->assertEquals($user->id, $updatedArticle->getAuthorId()->toString());
    $this->assertEquals($categoryId, $updatedArticle->getCategoryId()->toString());

    // タグの比較
    $savedTagIds = array_map(function ($tagId) {
      return $tagId->toString();
    }, $updatedArticle->getTags()->toArray());

    $this->assertCount(count($newTagIds), $savedTagIds);
    foreach ($newTagIds as $tag) {
      $this->assertTrue(in_array($tag, $savedTagIds));
    }

    $this->assertEquals('Published', $updatedArticle->getStatus()->toString());
  }
}
