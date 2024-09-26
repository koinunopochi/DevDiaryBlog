<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetOgpByUrlUseCase;
use App\Domain\ValueObjects\Url;
use App\Domain\Exceptions\OgpFetchException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class GetOgpByUrlController extends Controller
{
  private GetOgpByUrlUseCase $useCase;

  public function __construct(GetOgpByUrlUseCase $useCase)
  {
    $this->useCase = $useCase;
  }

  public function execute(Request $request): JsonResponse
  {
    try {
      $url = new Url($request->input('url'));
      $result = $this->useCase->execute($url);

      return response()->json($result->toArray(), 200);
    } catch (InvalidArgumentException $e) {
      return response()->json(['error' => 'URLの形式が正しくありません'], 400);
    } catch (OgpFetchException $e) {
      $statusCode = $e->getStatusCode() ?? 500;
      return response()->json(['error' => $e->getMessage()], $statusCode);
    } catch (\Exception $e) {
      return response()->json(['error' => 'サーバーエラーが発生しました'], 500);
    }
  }
}
