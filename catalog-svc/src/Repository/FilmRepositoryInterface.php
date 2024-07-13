<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Film;
use App\Exception\FilmNotFoundException;

interface FilmRepositoryInterface
{
    /**
     * @throws FilmNotFoundException
     */
    public function getById(int $filmId): Film;

    /**
     * @return Film[]
     */
    public function search(string $searchString): array;
}
