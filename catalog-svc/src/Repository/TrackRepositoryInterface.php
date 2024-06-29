<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Track;
use App\Exception\TrackNotFoundException;

interface TrackRepositoryInterface
{
    /**
     * @throws TrackNotFoundException
     */
    public function getById(int $trackId): Track;

    /**
     * @return Track[]
     */
    public function search(string $searchString): array;
}
