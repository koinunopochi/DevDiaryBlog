<?php

namespace Tests\Feature\Application\UseeCases;

use App\Application\UseCases\GetOgpByUrlUseCase;
use App\Domain\Repositories\OgpRepositoryInterface;
use App\Domain\ValueObjects\Ogp;
use App\Domain\ValueObjects\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GetOgpByUrlUseCaseTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */
  public function testGetOgpByUrl()
  {
    // Given
    $urlString = 'https://example.com';
    $url = new Url($urlString);
    $ogp = new Ogp(
      url: $url,
      imageUrl: new Url('https://example.com/test.png'),
      title: 'test title',
    );

    /** @var OgpRepositoryInterface&\Mockery\MockInterface $ogpRepository */
    $ogpRepository = Mockery::mock(OgpRepositoryInterface::class);
    $ogpRepository->shouldReceive('getByUrl')
      ->once()
      ->with(Mockery::on(function ($arg) use ($url) {
        return $arg instanceof Url && $arg->toString() === $url->toString();
      }))
      ->andReturn($ogp);

    $useCase = new GetOgpByUrlUseCase($ogpRepository);

    // When
    $result = $useCase->execute($url);

    // Then
    $this->assertInstanceOf(Ogp::class, $result);
    $this->assertSame($urlString, $result->getUrl()->toString());
    $this->assertEquals('https://example.com/test.png', $result->getImageUrl()->toString());
    $this->assertEquals('test title', $result->getTitle());
  }
}
