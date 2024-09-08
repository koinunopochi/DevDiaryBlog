<?php

namespace App\Http\Controllers;

use App\Application\UseCases\ExistsByNameUseCase;
use App\Domain\ValueObjects\Username;
use Illuminate\Http\Request;

class ExistsByNameController extends Controller
{

  private ExistsByNameUseCase $existsByNameUseCase;

  public function __construct(ExistsByNameUseCase $existsByNameUseCase)
  {
    $this->existsByNameUseCase = $existsByNameUseCase;
  }

  public function execute(Request $request)
  {
    try {
      $username = new Username($request->input("name"));
      // note: 利用可能性のため、存在しているUser名は利用できないため反転させる
      return response()->json(["available"=> !$this->existsByNameUseCase->execute($username)]);
    } catch (\Exception $e) {
      if ($e instanceof \InvalidArgumentException) {
        return response()->json(["error" => $e->getMessage()], 400);
      }
      return response()->json(['error' =>  $e->getMessage()], 500);
    }
  }
}
