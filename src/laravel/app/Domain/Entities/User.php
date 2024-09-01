<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Username;
use App\Domain\ValueObjects\UserStatus;
use App\Domain\ValueObjects\DateTime;

class User
{
  private UserId $id;
  private Username $username;
  private Email $email;
  private Password $password;
  private UserStatus $status;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(
    UserId $id,
    Username $username,
    Email $email,
    Password $password,
    UserStatus $status,
    DateTime $createdAt,
    DateTime $updatedAt,
  ) {
    $this->id = $id;
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
    $this->status = $status;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getUserId(): UserId
  {
    return $this->id;
  }

  public function getUsername(): Username
  {
    return $this->username;
  }

  public function getEmail(): Email
  {
    return $this->email;
  }

  public function getPassword(): Password
  {
    return $this->password;
  }

  public function getStatus(): UserStatus
  {
    return $this->status;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id->toString(),
      'username' => $this->username->toString(),
      'email' => $this->email->toString(),
      'status' => $this->status->toString(),
      'createdAt' => $this->createdAt->toString(),
      'updatedAt' => $this->updatedAt->toString(),
    ];
  }
}
