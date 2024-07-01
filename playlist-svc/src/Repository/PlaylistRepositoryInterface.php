<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Playlist;

interface PlaylistRepositoryInterface
{
    public function findPlaylistById(int $playlistId): Playlist;

    /**
     * @return Playlist[]
     */
    public function findPlaylistsByOwner(int $owner): array;

    public function createNewPlaylist(int $owner, string $name): void;

    public function save(Playlist $playlist): void;
}
