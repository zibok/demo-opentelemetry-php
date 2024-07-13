<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Favlist;
use App\Entity\VO\FilmId;
use App\Exception\FavlistNotFoundException;
use Doctrine\DBAL\Connection;

final class DBFavlistRepository implements FavlistRepositoryInterface
{
    private const TABLE_NAME = 'favlist';

    public function __construct(private Connection $dbConnection)
    {
    }

    public function findFavlistById(int $favlistId): Favlist
    {
        $qb = $this->dbConnection->createQueryBuilder();
        $result = $qb->select('id', 'name', 'owner')
                     ->addSelect('array_to_json(film_list) as "filmListJson"')
                     ->from(self::TABLE_NAME)
                     ->where($qb->expr()->eq('id', $favlistId))
                     ->executeQuery();

        $rows = $result->fetchAllAssociative();

        if (1 !== count($rows)) {
            throw new FavlistNotFoundException("Unable to find favlist $favlistId");
        }

        return new Favlist(
            $rows[0]['id'],
            $rows[0]['name'],
            $rows[0]['owner'],
            array_map(
                fn ($item) => new FilmId($item),
                is_null($rows[0]['filmListJson']) ? [] : json_decode($rows[0]['filmListJson']),
            )
        );
    }

    /**
     * @return Favlist[]
     */
    public function findFavlistsByOwner(int $owner): array
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();
        $result = $queryBuilder->select('id', 'name', 'owner')
                               ->addSelect('array_to_json(film_list) as "filmListJson"')
                               ->from(self::TABLE_NAME)
                               ->where($queryBuilder->expr()->eq('owner', $owner))
                               ->executeQuery();
        $favlists = [];
        foreach ($result->fetchAllAssociative() as $row) {
            $filmList = [];
            $filmIds = is_null($row['filmListJson']) ? [] : json_decode($row['filmListJson']);
            /** @var int $filmId */
            foreach ($filmIds as $filmId) {
                $filmList[] = new FilmId($filmId);
            }
            $favlists[] = new Favlist($row['id'], $row['name'], $row['owner'], $filmList);
        }

        return $favlists;
    }

    /**
     * @throws \Exception
     */
    public function createNewFavlist(int $owner, string $name): void
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

    public function save(Favlist $favlist): void
    {
        $qb = $this->dbConnection->createQueryBuilder();

        $qb->update(self::TABLE_NAME)
            ->set('name', ':name')
            ->set('owner', ':owner')
            ->set('film_list', ':filmList')
            ->where($qb->expr()->eq('id', $favlist->getId()))
            ->setParameters(
                [
                    'name' => $favlist->getName(),
                    'owner' => $favlist->getOwner(),
                    'filmList' => $this->array_to_postgresql_array(
                        array_map(
                            fn (FilmId $item) => $item->id,
                            $favlist->getFilmIdList(),
                        ),
                    ),
                ],
            )
            ->executeStatement();
    }

    private function array_to_postgresql_array(array $source): string
    {
        return '{'.implode(',', $source).'}';
    }
}
