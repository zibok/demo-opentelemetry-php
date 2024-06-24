<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Playlist;
use App\Entity\VO\Track;
use Doctrine\DBAL\Connection;

final class DBPlaylistRepository implements PlaylistRepositoryInterface
{
    private const TABLE_NAME = 'mmm_playlist';

    public function __construct(private Connection $dbConnection)
    {
    }

    /**
     * @return Playlist[]
     */
    public function findPlaylistsByOwner(int $owner): array
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();
        $result = $queryBuilder->select([
                                    'id',
                                    'name',
                                    'owner',
                               ])
                               ->addSelect('array_to_json(track_list) as "trackListJson"')
                               ->from(self::TABLE_NAME)
                               ->where($queryBuilder->expr()->eq('owner', $owner))
                               ->executeQuery();
        $playlists = [];
        foreach($result->fetchAllAssociative() as $row) {
            $trackList = [];
            if (is_null($row['trackListJson'])) {
                $trackIds = [];    
            } else {
                $trackIds = json_decode($row['trackListJson']);
            }
            /** @var int $trackId */
            foreach($trackIds as $trackId) {
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
                        'owner'=> '?',
                     ])
                     ->setParameter(0, $name)
                     ->setParameter(1, $owner)
                     ->executeStatement();
    }
}