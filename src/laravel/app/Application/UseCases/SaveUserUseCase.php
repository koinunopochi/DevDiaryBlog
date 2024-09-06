<?php

namespace App\Application\UseCases;

use App\Application\DataTransferObjects\SaveUserDTO;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\DateTime;

class SaveUserUseCase
{
  private UserRepositoryInterface $userRepository;

  public function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function execute(SaveUserDTO $dto, UserId $userId)
  {
    $oldUser = $this->userRepository->findById($userId);

    // note : DTO から新しい値を取得、存在しない場合は古い値を使用
    $name = $dto->getName() ?? $oldUser->getUsername();
    $email = $dto->getEmail() ?? $oldUser->getEmail();
    $password = $dto->getPassword() ?? $oldUser->getPassword();

    $user = new User(
      $userId,
      $name,
      $email,
      $password,
      $oldUser->getStatus(),
      $oldUser->getCreatedAt(),
      new DateTime()
    );

    $this->userRepository->save($user);
  }
}
