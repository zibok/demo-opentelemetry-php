<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Playlist;

interface PlaylistRepositoryInterface
{
    /**
     * @return Playlist[]
     */
    public function findPlaylistsByOwner(int $owner): array;

    public function createNewPlaylist(int $owner, string $name): void;
}
