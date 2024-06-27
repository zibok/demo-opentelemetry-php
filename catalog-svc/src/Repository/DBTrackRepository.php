<?php

declare(strict_types=1);

namespace App\Repository;
use App\Entity\Track;
use App\Exception\TrackNotFoundException;
use Doctrine\DBAL\Connection;

final class DBTrackRepository implements TrackRepositoryInterface
{
    public function __construct(private Connection $dbConnection)
    {
    }

    /**
     * @throws TrackNotFoundException
     */
    public function getById(int $trackId): Track
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();

        $result = $queryBuilder->select('t.id', 't.title', 't.author', 't.link')
                               ->from('mmm_track', 't')
                               ->where('id = :track_id')
                               ->setParameter('track_id', $trackId)
                               ->executeQuery();

        $row = $result->fetchAllAssociative();
        if (count($row) === 0) {
            throw new TrackNotFoundException("Track not found ($trackId)");
        }

        return new Track($row[0]['id'], $row[0]['title'], $row[0]['author'], $row[0]['link']);
    }
}
