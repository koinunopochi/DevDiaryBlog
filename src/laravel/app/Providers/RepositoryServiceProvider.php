<?php

namespace App\Providers;

use App\Domain\Repositories\ArticleRepositoryInterface;
use App\Domain\Repositories\OgpRepositoryInterface;
use App\Domain\Repositories\ProfileIconRepositoryInterface;
use App\Domain\Repositories\UserProfileRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Ogp\HttpOgpRepository;
use App\Infrastructure\Persistence\EloquentArticleRepository;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Infrastructure\Persistence\MinioProfileIconRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    $this->app->bind(UserProfileRepositoryInterface::class, EloquentUserProfileRepository::class);
    $this->app->bind(ProfileIconRepositoryInterface::class, MinioProfileIconRepository::class);
    $this->app->bind(OgpRepositoryInterface::class, HttpOgpRepository::class);
    $this->app->bind(ArticleRepositoryInterface::class, EloquentArticleRepository::class);
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
