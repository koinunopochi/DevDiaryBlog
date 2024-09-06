<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Config;

class S3FilePathCollection
{
  public const DEFAULT_BUCKET_PATH_FLAG = 'DEFAULT_BUCKET_PATH_FLAG';
  private string $bucketPath;
  private array $files;

  public function __construct(string $bucketPath, array $files)
  {
    if ($bucketPath === self::DEFAULT_BUCKET_PATH_FLAG) $bucketPath = $this->getDefaultBucketPath();

    $this->validate($bucketPath, $files);
    $this->bucketPath = $this->convertToLocalhostIfMinio($bucketPath);
    $this->files = $files;
  }

  private function getDefaultBucketPath(): string
  {
    return Config::get('filesystems.s3_default_bucket_path');
  }

  private function validate(string $bucketPath, array $files): void
  {
    if (!$bucketPath) {
      throw new \InvalidArgumentException('Bucket pathは空ではいけません');
    }
  }

  private function convertToLocalhostIfMinio(string $bucketPath): string
  {
    if (!str_starts_with($bucketPath, 'http://minio')) {
      return $bucketPath;
    }

    return str_replace('minio', 'localhost', $bucketPath);
  }

  public function toArray(): array
  {
    return [
      "bucketPath" => $this->bucketPath,
      "files" => $this->files,
    ];
  }
}
