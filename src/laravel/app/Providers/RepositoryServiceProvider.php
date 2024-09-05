<?php

namespace App\Providers;

use App\Domain\Repositories\UserProfileRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\EloquentUserProfileRepository;
use App\Infrastructure\Persistence\EloquentUserRepository;
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
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
