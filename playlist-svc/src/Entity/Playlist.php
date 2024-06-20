<?php

declare(strict_types=1);

namespace App\Entity;

final class Playlist
{
    public function __construct(
        private ?int $id,
        private string $name,
        private int $owner,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Playlist
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Playlist
    {
        $this->name = $name;
        return $this;
    }

    public function getOwner(): int
    {
        return $this->owner;
    }

    public function setOwner(int $owner): Playlist
    {
        $this->owner = $owner;
        return $this;
    }
}
