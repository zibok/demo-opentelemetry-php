<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\DBAL\Connection;

final readonly class DBUserRepository implements UserRepositoryInterface
{
    public function __construct(private Connection $dbConnection)
    {
    }

    /**
     * @return User[]
     *
     * @throws \Exception
     */
    public function findAll(): array
    {
        $queryBuilder = $this->dbConnection->createQueryBuilder();
        $result = $queryBuilder->select(['id', 'name'])
                              ->from('mmm_user')
                              ->executeQuery();

        $users = [];
        foreach ($result->fetchAllAssociative() as $row) {
            $users[] = new User($row['id'], $row['name']);
        }

        return $users;
    }

    public function getById(int $id): User
    {
        throw new UserNotFoundException("User not found ($id)");
    }

    public function save(User $user)
    {
        // TODO: Implement save() method.
    }
}
