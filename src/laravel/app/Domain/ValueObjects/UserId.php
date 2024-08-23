<?php

namespace App\Domain\ValueObjects;

class UserId
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function toString(): string
    {
        return $this->userId;
    }
}
