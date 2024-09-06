<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\ProfileIconRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MinioProfileIconRepository implements ProfileIconRepositoryInterface
{
  public function getDefaultAll(): array
  {
    $directory = '/profile-icons/defaults';
    Log::debug('class : MinioProfileIconRepository - method : getDefaultAll - $directory : ' . $directory);

    $files = Storage::disk('s3')->files($directory);
    Log::debug('class : MinioProfileIconRepository - method : getDefaultAll - $files : ' . json_encode($files, JSON_PRETTY_PRINT));
    return collect($files)->map(function ($file) {
      return $file;
    })->toArray();
  }
}
