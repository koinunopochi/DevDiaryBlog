<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\ProfileIconRepositoryInterface;
use App\Domain\ValueObjects\S3FilePathCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MinioProfileIconRepository implements ProfileIconRepositoryInterface
{
  public function getDefaultAll(): S3FilePathCollection
  {
    $directory = '/profile-icons/defaults';
    Log::debug('class : MinioProfileIconRepository - method : getDefaultAll - $directory : ' . $directory);

    $files = Storage::disk('s3')->files($directory);
    Log::debug('class : MinioProfileIconRepository - method : getDefaultAll - $files : ' . json_encode($files, JSON_PRETTY_PRINT));
    return new S3FilePathCollection(S3FilePathCollection::DEFAULT_BUCKET_PATH_FLAG, $files);
  }
}
