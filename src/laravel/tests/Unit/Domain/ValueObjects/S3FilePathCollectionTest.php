<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\S3FilePathCollection;
use Tests\TestCase;

class S3FilePathCollectionTest extends TestCase
{
  /**
   * @test
   */
  public function testCanBeCreated(): void
  {
    // Given
    $bucketPath = 'http://example.com/bucket';
    $files = ['file1.txt', 'file2.jpg'];

    // When
    $collection = new S3FilePathCollection($bucketPath, $files);

    // Then
    $this->assertInstanceOf(S3FilePathCollection::class, $collection);
  }

  /**
   * @test
   */
  public function testToArray(): void
  {
    // Given
    $bucketPath = 'http://example.com/bucket';
    $files = ['file1.txt', 'file2.jpg'];
    $collection = new S3FilePathCollection($bucketPath, $files);

    // When
    $result = $collection->toArray();

    // Then
    $this->assertEquals([
      'bucketPath' => $bucketPath,
      'files' => $files,
    ], $result);
  }

  /**
   * @test
   */
  public function testCannotBeCreatedWithEmptyBucketPath(): void
  {
    // Given
    $bucketPath = '';
    $files = ['file1.txt'];

    // Then
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Bucket pathは空ではいけません');

    // When
    new S3FilePathCollection($bucketPath, $files);
  }

  /**
   * @test
   */
  public function testConvertsMinioToLocalhost(): void
  {
    // Given
    $bucketPath = 'http://minio:9000/bucket';
    $files = ['file1.txt'];

    // When
    $collection = new S3FilePathCollection($bucketPath, $files);
    $result = $collection->toArray();

    // Then
    $this->assertEquals('http://localhost:9000/bucket', $result['bucketPath']);
  }

  /**
   * @test
   */
  public function testDoesNotConvertNonMinioBucketPath(): void
  {
    // Given
    $bucketPath = 'http://example.com/bucket';
    $files = ['file1.txt'];

    // When
    $collection = new S3FilePathCollection($bucketPath, $files);
    $result = $collection->toArray();

    // Then
    $this->assertEquals($bucketPath, $result['bucketPath']);
  }

  /**
   * @test
   */
  public function testUsesDefaultBucketPathWhenNotProvided(): void
  {
    // Given
    $files = ['file1.txt'];

    // When
    $collection = new S3FilePathCollection(S3FilePathCollection::DEFAULT_BUCKET_PATH_FLAG, $files);
    $result = $collection->toArray();

    // Then
    // note : 直接値を指定すると、DEFAULT_BUCKET_PATH_FLAGはあくまでもフラグなため一致しない
    // $this->assertEquals(S3FilePathCollection::DEFAULT_BUCKET_PATH_FLAG, $result['bucketPath']);

    $this->assertEquals('http://localhost:9000/dev-diary-blog', $result['bucketPath']);
  }
}
