<?php

namespace App\Infrastructure\Ogp;

use App\Domain\Repositories\OgpRepositoryInterface;
use App\Domain\ValueObjects\Ogp;
use App\Domain\ValueObjects\Url;
use App\Domain\Exceptions\OgpFetchException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class HttpOgpRepository implements OgpRepositoryInterface
{
  public function getByUrl(Url $url): Ogp
  {
    try {
      $response = Http::get($url->toString());
      $response->throw();
      $html = $response->body();

      $imageUrl = $this->extractImageUrl($html);
      $title = $this->extractTitle($html);

      return new Ogp(url: $url, imageUrl: $imageUrl, title: $title);
    } catch (RequestException $e) {
      $statusCode = $e->response->status();
      $message = "Failed to fetch OGP data: {$statusCode} " . $e->response->reason();
      throw new OgpFetchException($message, $statusCode, 0, $e);
    } catch (\Exception $e) {
      if (strpos($e->getMessage(), 'Could not resolve host') !== false) {
        throw new OgpFetchException('Failed to connect to the server', null, 0, $e);
      }
      throw new OgpFetchException('An error occurred while processing OGP data: ' . $e->getMessage(), null, 0, $e);
    }
  }

  private function extractImageUrl(string $html): ?Url
  {
    preg_match('/<meta property="og:image" content="(.*?)"/', $html, $imageMatches);
    if (isset($imageMatches[1])) {
      try {
        return new Url($imageMatches[1]);
      } catch (\Exception $e) {
        // Urlクラスで初期化できない場合はnull
        return null;
      }
    }
    return null;
  }

  private function extractTitle(string $html): ?string
  {
    preg_match('/<meta property="og:title" content="(.*?)"/', $html, $titleMatches);
    $title = $titleMatches[1] ?? null;

    if (!$title) {
      preg_match('/<title>(.*?)<\/title>/', $html, $titleMatches);
      $title = $titleMatches[1] ?? null;
    }

    return $title;
  }
}
