<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProfileIconRepositoryInterface;

class GetAllDefaultProfileIconsUseCase
{
  private $profileIconRepository;

  public function __construct(ProfileIconRepositoryInterface $profileIconRepository)
  {
    $this->profileIconRepository = $profileIconRepository;
  }

  public function execute(): array
  {
    // .envファイルからs3のエンドポイントを取得する
    $s3Endpoint = env('AWS_ENDPOINT');
    $defaultBucket = env('AWS_BUCKET');
    $bucketPath = $s3Endpoint . '/' . $defaultBucket;

    if (str_starts_with($s3Endpoint, 'http://minio')) {
      $bucketPath = str_replace('minio', 'localhost', $bucketPath);
    }

    // bucketPath + pathで完全な場所を返したい
    $defaultIcons = $this->profileIconRepository->getDefaultAll();
    $icons = [];
    foreach ($defaultIcons as $icon) {
      $icons[] = $bucketPath . '/' . $icon;
    }
    return $icons;
  }
}
