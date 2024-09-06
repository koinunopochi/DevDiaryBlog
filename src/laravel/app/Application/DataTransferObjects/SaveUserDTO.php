<?php

namespace App\Application\DataTransferObjects;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Domain\ValueObjects\Username;

class SaveUserDTO
{
  private ?Username $name;
  private ?Email $email;
  private ?Password $password;

  public function __construct(?Username $name, ?Email $email, ?Password $password)
  {
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
  }

  public function getName(): ?Username
  {
    return $this->name;
  }

  public function getEmail(): ?Email
  {
    return $this->email;
  }

  public function getPassword(): ?Password
  {
    return $this->password;
  }
}
