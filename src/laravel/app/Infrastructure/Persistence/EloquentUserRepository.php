<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Domain\ValueObjects\UserStatus;
use App\Domain\ValueObjects\DateTime;
use App\Models\User as EloquentUser;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
  /**
   * @inheritDoc
   */
  public function all(): Collection
  {
    return EloquentUser::all()->map(function (EloquentUser $eloquentUser) {
      return new User(
        new UserId($eloquentUser->id),
        new Username($eloquentUser->name),
        new Email($eloquentUser->email),
        new Password($eloquentUser->password),
        new UserStatus($eloquentUser->status),
        new DateTime($eloquentUser->created_at),
        new DateTime($eloquentUser->updated_at),
      );
    });
  }

  /**
   * @inheritDoc
   */
  public function findById(UserId $id): ?User
  {
    $eloquentUser = EloquentUser::find($id->toString());

    return $eloquentUser ? new User(
      new UserId($eloquentUser->id),
      new Username($eloquentUser->name),
      new Email($eloquentUser->email),
      new Password($eloquentUser->password),
      new UserStatus($eloquentUser->status),
      new DateTime($eloquentUser->created_at),
      new DateTime($eloquentUser->updated_at),
    ) : null;
  }

  /**
   * @inheritDoc
   */
  public function findByEmail(Email $email): ?User
  {
    $eloquentUser = EloquentUser::where('email', $email->toString())->first();

    return $eloquentUser ? new User(
      new UserId($eloquentUser->id),
      new Username($eloquentUser->name),
      new Email($eloquentUser->email),
      new Password($eloquentUser->password),
      new UserStatus($eloquentUser->status),
      new DateTime($eloquentUser->created_at),
      new DateTime($eloquentUser->updated_at),
    ) : null;
  }

  /**
   * @inheritDoc
   */
  public function findByName(Username $name): ?User
  {
    $eloquentUser = EloquentUser::where('name', $name->toString())->first();

    return $eloquentUser ? new User(
      new UserId($eloquentUser->id),
      new Username($eloquentUser->name),
      new Email($eloquentUser->email),
      new Password($eloquentUser->password),
      new UserStatus($eloquentUser->status),
      new DateTime($eloquentUser->created_at),
      new DateTime($eloquentUser->updated_at),
    ) : null;
  }

  /**
   * @inheritDoc
   */
  public function save(User $user): void
  {
    $eloquentUser = EloquentUser::find($user->getUserId()->toString()) ?? new EloquentUser();

    $eloquentUser->id = $user->getUserId()->toString();
    $eloquentUser->name = $user->getUsername()->toString();
    $eloquentUser->email = $user->getEmail()->toString();
    $eloquentUser->password = $user->getPassword()->toString();
    $eloquentUser->status = $user->getStatus()->toString();
    $eloquentUser->created_at = $user->getCreatedAt()->toString();
    $eloquentUser->updated_at = $user->getUpdatedAt()->toString();

    $eloquentUser->save();
  }

  /**
   * @inheritDoc
   */
  public function delete(User $user): void
  {
    $eloquentUser = EloquentUser::find($user->getUserId()->toString());

    if ($eloquentUser) {
      $eloquentUser->delete();
    }
  }
}
