<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;

class Cursor implements JsonSerializable
{
  private ArticleId $id;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(ArticleId $id, DateTime $createdAt, DateTime $updatedAt)
  {
    $this->id = $id;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getId(): ArticleId
  {
    return $this->id;
  }

  public function getCreatedAt(): DateTime
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->updatedAt;
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id->toString(),
      'createdAt' => $this->createdAt->toString(),
      'updatedAt' => $this->updatedAt->toString(),
    ];
  }

  public static function fromJson(string $json): self
  {
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new InvalidArgumentException('Invalid JSON string.');
    }

    if (!isset($data['id'], $data['createdAt'], $data['updatedAt'])) {
      throw new InvalidArgumentException('Missing required fields in JSON.');
    }

    return new self(new ArticleId($data['id']), new DateTime($data['createdAt']), new DateTime($data['updatedAt']));
  }

  public static function fromBase64(string $base64): self
  {
    $json = base64_decode($base64, true);

    if ($json === false) {
      throw new InvalidArgumentException('Invalid Base64 string.');
    }

    return self::fromJson($json);
  }

  public function toBase64(): string
  {
    return base64_encode(json_encode($this));
  }
}
