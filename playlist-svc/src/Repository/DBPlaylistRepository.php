<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Playlist;
use App\Entity\VO\Track;
use App\Exception\PlaylistNotFoundException;
use Doctrine\DBAL\Connection;

final class DBPlaylistRepository implements PlaylistRepositoryInterface
{
    private const TABLE_NAME = 'mmm_playlist';

    public function __construct(private Connection $dbConnection)
    {
    }

    public function findPlaylistById(int $playlistId): Playlist
    {
        $qb = $this->dbConnection->createQueryBuilder();
        $result = $qb->select('id', 'name', 'owner')
                     ->addSelect('array_to_json(track_list) as "trackListJson"')
                     ->from(self::TABLE_NAME)
                     ->where($qb->expr()->eq('id', $playlistId))
                     ->executeQuery();

        $rows = $result->fetchAllAssociative();

        if (1 !== count($rows)) {
            throw new PlaylistNotFoundException("Unable to find playlist $playlistId");
        }

        return new Playlist(
            $rows[0]['id'],
            $rows[0]['name'],
            $rows[0]['owner'],
            array_map(
                fn ($item) => new Track($item),
                is_null($rows[0]['trackListJson']) ? [] : json_decode($rows[0]['trackListJson']),
            )
        );
    }

    /**
     * @return Playlist[]
     */
    public function findPlaylistsByOwner(int $owner): array
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();
        $result = $queryBuilder->select('id', 'name', 'owner')
                               ->addSelect('array_to_json(track_list) as "trackListJson"')
                               ->from(self::TABLE_NAME)
                               ->where($queryBuilder->expr()->eq('owner', $owner))
                               ->executeQuery();
        $playlists = [];
        foreach ($result->fetchAllAssociative() as $row) {
            $trackList = [];
            $trackIds = is_null($row['trackListJson']) ? [] : json_decode($row['trackListJson']);
            /** @var int $trackId */
            foreach ($trackIds as $trackId) {
                $trackList[] = new Track($trackId);
            }
            $playlists[] = new Playlist($row['id'], $row['name'], $row['owner'], $trackList);
        }

        return $playlists;
    }

    /**
     * @throws \Exception
     */
    public function createNewPlaylist(int $owner, string $name): void
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();
        $queryBuilder->insert(self::TABLE_NAME)
                     ->values([
                         'name' => '?',
                         'owner' => '?',
                     ])
                     ->setParameter(0, $name)
                     ->setParameter(1, $owner)
                     ->executeStatement();
    }

    public function save(Playlist $playlist): void
    {
        $qb = $this->dbConnection->createQueryBuilder();

        $qb->update(self::TABLE_NAME)
            ->set('name', ':name')
            ->set('owner', ':owner')
            ->set('track_list', ':trackList')
            ->where($qb->expr()->eq('id', $playlist->getId()))
            ->setParameters(
                [
                    'name' => $playlist->getName(),
                    'owner' => $playlist->getOwner(),
                    'trackList' => $this->array_to_postgresql_array(
                        array_map(
                            fn (Track $item) => $item->id,
                            $playlist->getTrackList(),
                        ),
                    ),
                ],
            )
            ->executeStatement();
    }

    private function array_to_postgresql_array(array $source): string
    {
        return '{' . implode(',', $source) . '}';
    }
}
