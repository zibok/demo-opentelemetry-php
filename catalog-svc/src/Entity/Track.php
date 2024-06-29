<?php

declare(strict_types=1);

namespace App\Entity;

final class Track
{
    public function __construct(
        private int $id,
        private string $title,
        private string $author,
        private string $link,
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

    public function getLink(): string
    {
        return $this->link;
    }

    public function setId(int $id): Track
    {
        $this->id = $id;

        return $this;
    }

    public function setTitle(string $title): Track
    {
        $this->title = $title;

        return $this;
    }

    public function setAuthor(string $author): Track
    {
        $this->author = $author;

        return $this;
    }

    public function setLink(string $link): Track
    {
        $this->link = $link;

        return $this;
    }
}
