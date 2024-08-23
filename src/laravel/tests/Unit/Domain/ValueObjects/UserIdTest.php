<?php

namespace Tests\Unit\Domain\ValueObjects;

use Tests\TestCase;
use App\Domain\ValueObjects\UserId;
use Ramsey\Uuid\Uuid;
class UserIdTest extends TestCase
{
    /**
     * @test
     */
    public function testToString(): void
    {
        // Given
        $userId = "user-" . Uuid::uuid4()->toString();

        // When
        $userIdValueObject = new UserId($userId);

        // Then
        $this->assertEquals($userId, $userIdValueObject->toString());
    }
}
