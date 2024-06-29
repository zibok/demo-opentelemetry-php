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
        if (0 === count($row)) {
            throw new TrackNotFoundException("Track not found ($trackId)");
        }

        return new Track($row[0]['id'], $row[0]['title'], $row[0]['author'], $row[0]['link']);
    }

    /**
     * @return Track[]
     */
    public function search(string $searchString): array
    {
        $qb = $this->dbConnection->createQueryBuilder();

        $qb->select('t.id', 't.title', 't.author', 't.link')
                     ->from('mmm_track', 't')
                     ->setMaxResults(10);

        if ('' !== $searchString) {
            $qb->where(
                $qb->expr()->or(
                    $qb->expr()->like('title', ':search'),
                    $qb->expr()->like('author', ':search'),
                )
            )
            ->setParameter('search', "%{$searchString}%");
        }

        $sqlResult = $qb->executeQuery();

        return array_map(
            fn ($row) => new Track($row['id'], $row['title'], $row['author'], $row['link']),
            $sqlResult->fetchAllAssociative(),
        );
    }
}
