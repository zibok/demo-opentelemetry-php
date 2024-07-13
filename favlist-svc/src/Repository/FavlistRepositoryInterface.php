<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Favlist;

interface FavlistRepositoryInterface
{
    public function findFavlistById(int $playlistId): Favlist;

    /**
     * @return Favlist[]
     */
    public function findFavlistsByOwner(int $owner): array;

    public function createNewFavlist(int $owner, string $name): void;

    public function save(Favlist $playlist): void;
}
