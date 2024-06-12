<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;

final readonly class UserController
{
    public function __construct(private UserRepository $repository)
    {
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    function list(): Response
    {
        $users = $this->repository->findAll();

        $items = [];
        foreach ($users as $user) {
            $items[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
            ];
        }

        return new JsonResponse([
            "items" => $items,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    function create(): Response
    {
        throw HttpException::fromStatusCode(501, '/create is not implemented yet');
    }
}
