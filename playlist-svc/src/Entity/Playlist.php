<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\VO\Track;

final class Playlist
{
    /**
     * @param Track[] $trackList
     */
    public function __construct(
        private ?int $id,
        private string $name,
        private int $owner,
        private array $trackList,
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

    /**
     * @return Track[]
     */
    public function getTrackList(): array
    {
        return $this->trackList;
    }

    /**
     * @param Track[] $trackList
     */
    public function setTrackList(array $trackList): Playlist
    {
        $this->trackList = $trackList;

        return $this;
    }
}
