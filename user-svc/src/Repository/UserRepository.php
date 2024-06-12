<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

interface UserRepository
{
    /**
     * @return User[]
     */
    function findAll(): array;

    /**
     * @throws UserNotFoundException
     */
    function getById(int $id): User;

    function save(User $user);
}