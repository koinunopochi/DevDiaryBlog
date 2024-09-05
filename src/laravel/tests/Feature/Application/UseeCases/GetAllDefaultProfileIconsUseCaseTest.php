<?php

namespace Tests\Feature\Application\UseCases;

use Tests\TestCase;
use App\Application\UseCases\GetAllDefaultProfileIconsUseCase;
use App\Infrastructure\Persistence\MinioProfileIconRepository;

class GetAllDefaultProfileIconsUseCaseTest extends TestCase
{
  public function testGetAllDefaultProfileIcons()
  {
    // Given
    $repository = new MinioProfileIconRepository();
    $useCase = new GetAllDefaultProfileIconsUseCase($repository);

    // When
    $icons = $useCase->execute();

    // Then
    $this->assertNotEmpty($icons);
  }
}
