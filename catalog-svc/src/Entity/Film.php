<?php

declare(strict_types=1);

namespace App\Entity;

final class Film
{
    public function __construct(
        private int $id,
        private string $title,
        private string $author,
        private string $genre,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setId(int $id): Film
    {
        $this->id = $id;

        return $this;
    }

    public function setTitle(string $title): Film
    {
        $this->title = $title;

        return $this;
    }

    public function setAuthor(string $author): Film
    {
        $this->author = $author;

        return $this;
    }

    public function setGenre(string $genre): Film
    {
        $this->genre = $genre;

        return $this;
    }
}
