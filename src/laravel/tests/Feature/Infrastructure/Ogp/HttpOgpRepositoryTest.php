<?php

namespace Tests\Feature\Infrastructure\Ogp;

use App\Domain\ValueObjects\Url;
use App\Infrastructure\Ogp\HttpOgpRepository;
use Tests\TestCase;
use Symfony\Component\Process\Process;
use App\Domain\Exceptions\OgpFetchException;

class HttpOgpRepositoryTest extends TestCase
{
  private $serverProcess;

  protected function setUp(): void
  {
    parent::setUp();
    $this->startMockServer();
  }

  protected function tearDown(): void
  {
    $this->stopMockServer();
    parent::tearDown();
  }

  private function startMockServer()
  {
    $this->serverProcess = new Process(['php', '-S', 'localhost:8080', '-t', 'tests/MockServer']);
    $this->serverProcess->start();
    usleep(100000);
  }

  private function stopMockServer()
  {
    if ($this->serverProcess) {
      $this->serverProcess->stop();
    }
  }

  /**
   * @test
   */
  public function testGetByUrl_正常系()
  {
    $url = new Url('http://localhost:8080/normal');
    $repository = new HttpOgpRepository();

    $ogp = $repository->getByUrl($url);

    $this->assertEquals($url, $ogp->getUrl());
    $this->assertEquals('Normal OG Title', $ogp->getTitle());
    $this->assertEquals('https://example.com/normal-image.jpg', $ogp->getImageUrl()->toString());
  }

  /**
   * @test
   */
  public function testGetByUrl_OGタグがない場合()
  {
    $url = new Url('http://localhost:8080/no-og-tags');
    $repository = new HttpOgpRepository();

    $ogp = $repository->getByUrl($url);

    $this->assertEquals($url, $ogp->getUrl());
    $this->assertEquals('Page Without OG Tags', $ogp->getTitle());
    $this->assertNull($ogp->getImageUrl());
  }

  /**
   * @test
   */
  public function testGetByUrl_ページが見つからない場合()
  {
    $url = new Url('http://localhost:8080/not-found');
    $repository = new HttpOgpRepository();

    $this->expectException(OgpFetchException::class);
    $this->expectExceptionMessage('Failed to fetch OGP data: 404 Not Found');

    $repository->getByUrl($url);
  }

  /**
   * @test
   */
  public function testGetByUrl_サーバーエラーの場合()
  {
    $url = new Url('http://localhost:8080/server-error');
    $repository = new HttpOgpRepository();

    $this->expectException(OgpFetchException::class);
    $this->expectExceptionMessage('Failed to fetch OGP data: 500 Internal Server Error');

    $repository->getByUrl($url);
  }

  /**
   * @test
   */
  public function testGetByUrl_接続できない場合()
  {
    $url = new Url('http://non-existent-domain.example');
    $repository = new HttpOgpRepository();

    $this->expectException(OgpFetchException::class);
    $this->expectExceptionMessage('Failed to connect to the server');

    $repository->getByUrl($url);
  }
}
