<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Film;
use App\Exception\FilmNotFoundException;
use Doctrine\DBAL\Connection;

final class DBFilmRepository implements FilmRepositoryInterface
{
    public function __construct(private Connection $dbConnection)
    {
    }

    /**
     * @throws FilmNotFoundException
     */
    public function getById(int $filmId): Film
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();

        $result = $queryBuilder->select('t.id', 't.title', 't.author', 't.genre')
                               ->from('film', 't')
                               ->where('id = :film_id')
                               ->setParameter('film_id', $filmId)
                               ->executeQuery();

        $row = $result->fetchAllAssociative();
        if (0 === count($row)) {
            throw new FilmNotFoundException("Film not found ($filmId)");
        }

        return new Film($row[0]['id'], $row[0]['title'], $row[0]['author'], $row[0]['genre']);
    }

    /**
     * @return Film[]
     */
    public function search(string $searchString): array
    {
        $qb = $this->dbConnection->createQueryBuilder();

        $qb->select('t.id', 't.title', 't.author', 't.genre')
                     ->from('film', 't')
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
            fn ($row) => new Film($row['id'], $row['title'], $row['author'], $row['genre']),
            $sqlResult->fetchAllAssociative(),
        );
    }
}
