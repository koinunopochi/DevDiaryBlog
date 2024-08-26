<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\PolicyDescription;
use App\Domain\ValueObjects\PolicyDocument;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyName;

class Policy
{
    private PolicyId $id;
    private PolicyName $name;
    private PolicyDescription $description;
    private PolicyDocument $document;

    public function __construct(
        PolicyId $id,
        PolicyName $name,
        PolicyDescription $description,
        PolicyDocument $document
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->document = $document;
    }

    public function getId(): PolicyId
    {
        return $this->id;
    }

    public function getName(): PolicyName
    {
        return $this->name;
    }

    public function getDescription(): PolicyDescription
    {
        return $this->description;
    }

    public function getDocument(): PolicyDocument
    {
        return $this->document;
    }
}

