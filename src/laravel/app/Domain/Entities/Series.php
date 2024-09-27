<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\SeriesId;
use App\Domain\ValueObjects\SeriesTitle;
use App\Domain\ValueObjects\SeriesDescription;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\SeriesStatus;
use App\Domain\ValueObjects\DateTime;

class Series
{
  private SeriesId $id;
  private SeriesTitle $title;
  private SeriesDescription $description;
  private UserId $authorId;
  private SeriesStatus $status;
  private DateTime $createdAt;
  private DateTime $updatedAt;

  public function __construct(
    SeriesId $id,
    SeriesTitle $title,
    SeriesDescription $description,
    UserId $authorId,
    SeriesStatus $status,
    DateTime $createdAt,
    DateTime $updatedAt
  ) {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->authorId = $authorId;
    $this->status = $status;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  public function getId(): SeriesId
  {
    return $this->id;
  }

  public function getTitle(): SeriesTitle
  {
    return $this->title;
  }

  public function getDescription(): SeriesDescription
  {
    return $this->description;
  }

  public function getAuthorId(): UserId
  {
    return $this->authorId;
  }

  public function getStatus(): SeriesStatus
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

  public function updateTitle(SeriesTitle $title, DateTime $updatedAt): self
  {
    return new self($this->id, $title, $this->description, $this->authorId, $this->status, $this->createdAt, $updatedAt);
  }

  public function updateDescription(SeriesDescription $description, DateTime $updatedAt): self
  {
    return new self($this->id, $this->title, $description, $this->authorId, $this->status, $this->createdAt, $updatedAt);
  }

  public function updateStatus(SeriesStatus $status, DateTime $updatedAt): self
  {
    return new self($this->id, $this->title, $this->description, $this->authorId, $status, $this->createdAt, $updatedAt);
  }
}
