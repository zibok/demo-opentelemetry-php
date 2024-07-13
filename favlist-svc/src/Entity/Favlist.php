<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\VO\FilmId;

final class Favlist
{
    /**
     * @param FilmId[] $filmIdList
     */
    public function __construct(
        private ?int $id,
        private string $name,
        private int $owner,
        private array $filmIdList,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Favlist
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Favlist
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): int
    {
        return $this->owner;
    }

    public function setOwner(int $owner): Favlist
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return FilmId[]
     */
    public function getFilmIdList(): array
    {
        return $this->filmIdList;
    }

    /**
     * @param FilmId[] $filmIdList
     */
    public function setFilmIdList(array $filmIdList): Favlist
    {
        $this->filmIdList = $filmIdList;

        return $this;
    }
}
