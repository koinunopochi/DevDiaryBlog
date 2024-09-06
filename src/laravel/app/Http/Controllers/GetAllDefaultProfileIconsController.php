<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetAllDefaultProfileIconsUseCase;

class GetAllDefaultProfileIconsController extends Controller
{

  private GetAllDefaultProfileIconsUseCase $allDefaultProfileIconsUseCase;
  public function __construct(GetAllDefaultProfileIconsUseCase $getAllDefaultProfileIconsUseCase)
  {
    $this->allDefaultProfileIconsUseCase = $getAllDefaultProfileIconsUseCase;
  }

  public function execute()
  {
    try {
      $result = $this->allDefaultProfileIconsUseCase->execute();
      return response()->json(['icons'=>$result->toArray()]);
    } catch (\Exception $e) {
      return response()->json(['error' =>  $e->getMessage()], 500);
    }
  }
}
