<?php

namespace App\Models;

final class Supplier
{
    /**
     * @param string $name
     * @param string $description
     */
    public function __construct(
        private string        $name,
        private string        $description
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}